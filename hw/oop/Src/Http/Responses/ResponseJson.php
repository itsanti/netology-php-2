<?php

namespace App\Http\Responses;


class ResponseJson extends Response implements IResponces {

    public function __construct() {
        $this->setHeader('Content-Type', 'application/json');
    }

    public function setBody($content = '') {
        $this->body = $content;
    }

    public function getBody($render = null) {
        return json_encode($this->body, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT);
    }
}
