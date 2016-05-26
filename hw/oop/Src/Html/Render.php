<?php

namespace App\Html;

class Render {

    const DEFAULT_LAYOUT = 'index.html';

    private $tplvars = [];
    private $layout = self::DEFAULT_LAYOUT;
    
    public function __construct($tplvars = [], $layout = self::DEFAULT_LAYOUT)
    {
        $this->layout = $layout;

        if (!empty($tplvars)) {
            $this->setTplVars($tplvars, $layout);            
        }
    }

    public function setTplVars($vars)
    {
        $this->tplvars[$this->layout] = $vars;
    }
    
    public function render($xssafe = false)
    {
        $data = $this->getLayout($this->layout);

        if (!is_null($data['placeholders'])) {
            foreach ($data['placeholders'] as $placeholder) {
                if (array_key_exists($placeholder, $this->tplvars[$this->layout])) {
                    $var = $this->tplvars[$this->layout][$placeholder];
                    if ($xssafe) {
                        $var = $this->xssafe($var);
                    }
                    $data['content'] = str_replace("{{{$placeholder}}}", $this->prepareVar($var), $data['content']);
                } else {
                    $data['content'] = str_replace("{{{$placeholder}}}", '', $data['content']);
                }
            }
        }
        
        return $data['content'];
    }

    private function getLayout($name)
    {
        $placeholders = null;
        $content = '';

        $file = \ROOT . '/Src/Templates/' . $name;

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
    
    private function prepareVar($var)
    {
        return str_replace(['{', '}'], ['&#x7B;', '&#x7D;'], $var);
    }
    
    private function xssafe($data, $encoding='UTF-8')
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
    }
}
