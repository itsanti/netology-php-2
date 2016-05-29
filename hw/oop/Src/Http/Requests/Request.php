<?php

namespace App\Http\Requests;


abstract class Request {

    protected static $type = 'html';
    
    public static function build()
    {
        $accept = self::getHeader('Accept');
        preg_match('~text/html|application/json~i', $accept, $matches);
        if(!empty($matches)) {
            $accept = $matches[0];
        }
        switch ($accept) {
            case 'application/json':
                self::$type = 'json';
                return new RequestJson();
            case 'text/html':
            default:
                return new RequestHtml();
        }
    }

    public function getRequestTarget() {
        return $_SERVER['REQUEST_URI'];
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getHeader($name) {
        $headers = getallheaders();
        if (array_key_exists($name, $headers)) {
            return $headers[$name];
        }
        return '';
    }

    public function getHeaders() {
        return getallheaders();
    }
    
    public function getType()
    {
        return self::$type;
    }

    abstract public function getBody();
}
