<?php

namespace App\Http\Requests;

class RequestJson extends Request {
    public function getBody() {
        return json_decode(file_get_contents("php://input"), true);
    }
}
