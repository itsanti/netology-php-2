<?php

namespace App\Http\Responses;


class ResponseHtml extends Response {

    public function setBody($content = '') {
        $this->body = $content;
    }

    public function getBody($render = null) {

        if (!is_null($render)) {
            $tpl = $this->body['tpl'];
            unset($this->body['tpl']);
            return $render->render($tpl, $this->body);
        }

        return print_r($this->body, 1);
    }
}
