<?php

namespace App\Http;


class Response {
    
    private $content;
    private $headers = [];
    private $tplvars = [];

    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
        $header = $name . ': ' . $value;
        header($header);
    }

    public function getHeader($name) {
        $value = '';
        
        if (array_key_exists($name, $this->headers)) {
            $value = $this->headers[$name];
        }
        
        return $value;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function setBody($html) {
        $this->content = $html;
    }

    public function getBody($layout = 'index.html') {

        if (!$layout) {
            return $this->content;
        }

        // обязательная переменная шаблона
        $this->tplvars[$layout]['content'] = $this->content;

        $data = $this->getLayout($layout);

        if (!is_null($data['content']) && !is_null($data['placeholders'])) {
            foreach ($data['placeholders'] as $placeholder) {
                if (array_key_exists($placeholder, $this->tplvars[$layout])) {
                    $data['content'] = str_replace("{{{$placeholder}}}", $this->tplvars[$layout][$placeholder], $data['content']);
                } else {
                    $data['content'] = str_replace("{{{$placeholder}}}", '', $data['content']);
                }
            }
            return $data['content'];
        }

        return $this->content;
    }
    
    public function setTplVars($vars, $layout = 'index.html')
    {
        $this->tplvars[$layout] = $vars;
    }

    private function getLayout($name)
    {
        $placeholders = null;
        $content = null;

        $file = ROOT . '/Src/Templates/' . $name;
        if (is_readable($file)) {
            $content = file_get_contents($file);
        }

        preg_match_all('~{{(.+?)}}~', $content, $matches);

        if (!empty($matches[1])) {
            $placeholders = $matches[1];
        }

        return [
            'placeholders' => $placeholders,
            'content' => $content
        ];
    }
}
