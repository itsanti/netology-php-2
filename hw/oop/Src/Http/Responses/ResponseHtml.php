<?php

namespace App\Http\Responses;


class ResponseHtml extends Response implements IResponces {

    public function setBody($content = '') {
        $this->body = $content;
    }

    public function getBody($render = null) {

        if (!is_null($render)) {
            return $render->render();
        }

        return $this->body;
    }
}
