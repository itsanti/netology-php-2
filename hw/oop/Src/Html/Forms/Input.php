<?php

namespace App\Html\Forms;


class Input extends Element {

    public function __construct($type, $opt)
    {
        parent::__construct($type, $opt);
        $this->tpl =<<<HTML
<label for="{$this->opt['id']}">{$this->opt['text']}</label>
<input class="form-control input-sm" type="{$this->type}" id="{$this->opt['id']}"
    name="{$this->opt['name']}" required placeholder="{$this->opt['text']}" value="{$this->opt['tplvar']}">
HTML;
    }

}
