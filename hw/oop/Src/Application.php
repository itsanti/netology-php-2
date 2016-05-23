<?php
namespace App;

class Application
{
    /**
     * Метод, показывающий какое минимальное количество методов у вас должны быть реализовано в объекте Request
     * @param Http\Request $request
     */
    public function checkRequest(Http\Request $request)
    {
        $requestTarget = $request->getRequestTarget();
        $method        = $request->getMethod();
        $header        = $request->getHeader('Content-Type');
        $headers       = $request->getHeaders();
        $body          = $request->getBody();
    }

    /**
     * Метод, показывающий какое минимальное количество методов у вас должны быть реализовано в объекте Response
     *
     * @param Http\Response $response
     */
    public function checkResponse(Http\Response $response)
    {
        $response->setHeader('Content-Type', 'text/html');
        $header  = $response->getHeader('Content-Type');
        $headers = $response->getHeaders();
        $response->setBody('<h1>hi hi</h1>');
        $response->setTplVars(['title' => 'OOP task']);
        $body = $response->getBody();
    }

    public function execute(Http\Request $request, Http\Response $response)
    {
        $this->checkRequest($request);      // можно удалить, оставлено для примера
        
        $response->setBody('<h1>hi hi</h1>');
        $response->setTplVars(['title' => 'OOP task']);
        echo $response->getBody();
    }
}
