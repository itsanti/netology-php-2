<?php

namespace App\Html\Forms;


class Button extends Element {

    public function render()
    {
        $html =<<<HTML
<button class="btn {$this->opt['class']}" id="{$this->opt['id']}" type="{$this->type}">{$this->opt['text']}</button>
HTML;
        if (array_key_exists('wrapper', $this->opt)) {
            $html = $this->wrapper($html);
        }
        return $html;
    }
}
