<?php

namespace App\Http;


class Request {

    public function getRequestTarget() {
        // $requestTarget == '/index.php'
        return __FUNCTION__;
    }

    public function getMethod() {
        // $method == 'GET'
        return __FUNCTION__;
    }

    public function getHeader( $string ) {
        // $header == 'application/x-www-form-urlencoded'
        return __FUNCTION__;
    }

    public function getHeaders() {
        // array('Content-Type' => 'application/x-www-form-urlencoded')
        return __FUNCTION__;
    }

    public function getBody() {
        // $body == array('test' => 'value', 'submit' => 'Отправить')
        return __FUNCTION__;
    }
}