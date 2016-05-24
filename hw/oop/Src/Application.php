<?php
namespace App;

use App\Storages\Session;

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
        $body = $response->getBody();
    }

    public function execute(Http\Request $request, Http\Response $response)
    {
        if($request->getMethod() === 'POST') {
            Session::saveData('data', $request->getBody());
            $response->setHeader("Location", $request->getRequestTarget(), 303);
            exit;
        }

        $indexVars = [];
        $formVars = ['action' => $request->getRequestTarget()];
        $data = Session::loadData('data', true);

        if (!empty($data) && array_key_exists('user', $data)) {
            $formVars += $data['user'];
            $indexVars['msg'] = '<p class="bg-success" style="padding:10px;">Данные получены</p>';
        }

        $render = new Html\Render($formVars, 'form.html');
        $response->setBody($render->render());
        unset($render);

        $render = new Html\Render([
            'title' => 'OOP task',
            'content' => $response->getBody()
        ] + $indexVars);

        echo $response->getBody($render);
    }
}
