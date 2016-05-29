<?php

namespace App\Http\Requests;

class RequestHtml extends Request {
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
