<?php

namespace App\Html\Forms;

use App\Storages\Session;

class Form {

    private $fields = [];
    private $params = [];

    public function __construct($params, $fields) {
        $this->params = $params;
        $this->fields = $fields;
    }
    
    public function validate($values)
    {
        $errors = [];
        foreach ( $this->fields as $field ) {
            switch ($field['element'])
            {
                case 'input':
                    $elem  = new Input($field['type'], $field['opt']);
                    $e = $elem->validate($values[$field['opt']['name']]);
                    if(!empty($e))
                    {
                        $errors[$field['opt']['name']] = $e;
                    }
                    break;
            }
        }
        return $errors;
    }

    public function buildForm()
    {
        $html = '';
        foreach ( $this->fields as $field ) {
            $elem = null;
            switch ($field['element'])
            {
                case 'input':
                    $elem  = new Input($field['type'], $field['opt']);
                break;
                case 'button':
                    $elem  = new Button($field['type'], $field['opt']);
                break;
            }
            $html .= $elem->render();
        }
        return $this->render($html);
    }
    
    protected function render($content)
    {
        $html =<<<HTML
<form action="{$this->params['action']}" method="{$this->params['method']}" enctype="{$this->params['enctype']}">
{$content}
</form>
HTML;
        if (array_key_exists('wrapper', $this->params)) {
            $html = $this->params['wrapper']['b'] . $html . $this->params['wrapper']['e'];
        }
        return $html;
    }
}