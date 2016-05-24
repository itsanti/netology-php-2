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
    
    public function render()
    {
        $data = $this->getLayout($this->layout);

        if (!is_null($data['placeholders'])) {
            foreach ($data['placeholders'] as $placeholder) {
                if (array_key_exists($placeholder, $this->tplvars[$this->layout])) {
                    $data['content'] = str_replace("{{{$placeholder}}}", $this->tplvars[$this->layout][$placeholder], $data['content']);
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
}
