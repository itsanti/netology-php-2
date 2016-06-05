<?php

namespace App\Http\Responses;

interface IResponces {
    public function setBody($content = '');
    public function getBody($render = null);
    public function setHeader($name, $value);
    public function getHeader($name);
    public function getHeaders();
    public function sendHeaders($status = null);
}