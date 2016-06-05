<?php

namespace App\Html\Forms;


class Button extends Element {

    public function __construct($type, $opt)
    {
        parent::__construct($type, $opt);
        $this->tpl =<<<HTML
<button class="btn {$this->opt['class']}" id="{$this->opt['id']}" type="{$this->type}">{$this->opt['text']}</button>
HTML;
    }
}
