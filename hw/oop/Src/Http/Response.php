<?php

namespace App\Http;


class Response {

    private $body;
    private $headers = [];
    
    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
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
    
    public function sendHeaders($status = null) {
        
        $header = '';
        
        foreach ($this->headers as $name => $value) {
            $header = $name . ': ' . $value;
            header($header);
        }
        
        if (!is_null($status)) {
            http_response_code($status);
        }
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
