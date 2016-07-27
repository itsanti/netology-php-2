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
            case 'multipart/form-data':
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
        $name = explode('\\', static::class);
        return end($name);
    }

    public function checkAccess()
    {
        if (!empty($_SESSION['isAdmin'])) {
            return true;
        }
        return false;
    }

    abstract public function getBody();
}
