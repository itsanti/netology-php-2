<?php

namespace App\Http\Responses;


abstract class Response {

    protected $body;
    protected $headers = [];

    public static function build($request) {
        switch ($request) {
            case 'RequestJson':
                return new ResponseJson();
            case 'RequestHtml':
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

    /**
     * Вспомогательный метод для перенаправления.
     *
     * @param string $path путь для перенаправления
     * @param int $status код ответа
     */
    public function redirect($path, $status)
    {
        $this->setHeader('Location', $path . '/');
        $this->sendHeaders($status);
        exit;
    }
}
