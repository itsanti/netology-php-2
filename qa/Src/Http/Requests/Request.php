<?php

namespace App\Http\Requests;


abstract class Request {

    public static function build()
    {
        $type = self::getHeader('Content-Type');

        switch ($type) {
            case 'application/json':
                return new RequestJson();
            case 'application/x-www-form-urlencoded':
            default:
                return new RequestHtml();
        }
    }

    public function getRequestTarget() {
        return strtok($_SERVER['REQUEST_URI'],'?');
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

    public function __toString() {
        if ($pos = strrpos(static::class, '\\')) return substr(static::class, $pos + 1);
        return $pos;
    }

    abstract public function getBody();
}
