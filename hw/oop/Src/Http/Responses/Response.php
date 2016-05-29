<?php

namespace App\Http\Responses;


abstract class Response {

    protected $body;
    protected $headers = [];

    public static function build($type = '') {
        switch ($type) {
            case 'json':
                return new ResponseJson();
            case 'html':
            default:
                return new ResponseHtml();
        }
    }

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
}
