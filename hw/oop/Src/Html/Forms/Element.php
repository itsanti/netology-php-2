<?php

namespace App\Html\Forms;


class Element {
    protected $type;
    protected $opt;
    protected $tpl;
    
    public function __construct($type, $opt) {
        $this->type = $type;
        $this->opt = $opt;
    }
    
    public function validate($val)
    {
        if (array_key_exists('validate', $this->opt)) {
            if (!preg_match($this->opt['validate']['pattern'], $val)) {
                return $this->opt['validate']['text'];
            }
        }
        return '';
    }

    public function render()
    {
        if (array_key_exists('wrapper', $this->opt)) {
            return $this->wrapper($this->tpl);
        }
        return '';
    }

    /**
     * Функция оборачивает элемент в заданный html
     *
     * @param string $html разметка, которую нужно обернуть
     *
     * @return string
     */
    protected function wrapper($html)
    {
        if ($this->opt['wrapper']['to'] == 'b') {
            $html = $this->opt['wrapper']['html'] . $html;
        }
        if ($this->opt['wrapper']['to'] == 'e') {
            $html = $html . $this->opt['wrapper']['html'];
        }
        if ($this->opt['wrapper']['to'] == 'a') {
            $html = $this->opt['wrapper']['b'] . $html . $this->opt['wrapper']['e'];
        }

        return $html;
    }
}
