<?php

namespace App\Http\Responses;

interface IResponces {
    public function setBody($content = '');
    public function getBody($render = null);
}