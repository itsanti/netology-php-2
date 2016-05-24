<?php

namespace App\Http;


class Request {

    public function getRequestTarget() {
        return $_SERVER['REQUEST_URI'];
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getHeader($name) {
        $headers = getallheaders();
        if (array_key_exists($name, $headers)) {
            return $headers[$name];
        }
        return '';
    }

    public function getHeaders() {
        return getallheaders();
    }

    public function getBody() {
        switch ($this->getMethod())
        {
            case 'GET':
                return $_GET;
            break;
            case 'POST':
                return $_POST + $_GET;
            break;
            default:
                return [];
        }
    }
}
