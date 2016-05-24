<?php

namespace App\Http;


class Response {

    private $body;
    private $headers = [];
    
    public function setHeader($name, $value, $status = null) {
        
        $this->headers[$name] = $value;
        $header = $name . ': ' . $value;
        
        if (!is_null($status)) {
            header($header, true, $status);
        } else {
            header($header);
        }
    }

    public function getHeader($name) {
        $value = '';
        
        if (array_key_exists($name, $this->headers)) {
            $value = $this->headers[$name];
        }
        
        return $value;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function setBody($content) {
        $this->body = $content;
    }

    public function getBody($render = null) {

        if (!is_null($render)) {
            return $render->render();
        }

        return $this->body;
    }
}
