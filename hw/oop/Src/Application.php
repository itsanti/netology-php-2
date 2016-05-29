<?php
namespace App;

use App\Storages\Session;

class Application
{
    /**
     * Метод, показывающий какое минимальное количество методов у вас должны быть реализовано в объекте Request
     * @param Http\Requests\Request $request
     */
    public function checkRequest(Http\Requests\Request $request)
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
     * @param Http\Responses\IResponces $response
     */
    public function checkResponse(Http\Responses\IResponces $response)
    {
        $response->setHeader('Content-Type', 'text/html');
        $header  = $response->getHeader('Content-Type');
        $headers = $response->getHeaders();
        $response->setBody('<h1>hi hi</h1>');
        $body = $response->getBody();
    }

    public function execute(Http\Requests\Request $request, Http\Responses\IResponces $response)
    {
        list($params, $fields) = $this->loadForm();
        $form = new Html\Forms\Form($params, $fields);

        if($request->getMethod() === 'POST') {
            $values = $request->getBody();

            switch ($request->getType()) {
                case 'json':
                    $data = [];
                    foreach ( $values as $item ) {
                        $data[$item['name']] = $item['value'];
                    }
                    $errors = $form->validate($data);
                    $response->setBody($values + ["errors" => $errors]);
                    $response->sendHeaders();
                    echo $response->getBody();
                    exit;
                case 'html':
                default:
                    $errors = $form->validate($values);
                    Session::saveData('data', $values);
                    Session::saveData('errors', $errors);
                    $response->setHeader("Location", $request->getRequestTarget());
                    $response->sendHeaders(303);
            }
            exit;
        }

        $indexVars = [];
        $formVars = ['action' => $request->getRequestTarget()];
        $data = Session::loadData('data', true);

        if (!empty($data)) {
            foreach ($data as $k => $value) {
                $formVars[explode('_', $k)[1]] = $value;
            }
            $indexVars['msg'] = '<p class="bg-success" style="padding:10px;">Данные получены</p>';
        }

        $render = new Html\Render($formVars, 'form', $form->buildForm());
        $response->setBody($render->render(true));
        unset($render);

        $render = new Html\Render([
            'title' => 'OOP task',
            'content' => $response->getBody(),
            'url' => $request->getRequestTarget()
        ] + $indexVars);

        $errors = Session::loadData('errors', true);
        
        if (!empty($errors)) {
            $render->setTplVars([
                'errors' => $render->renderArray($errors, 'danger')
            ]);
        }

        echo $response->getBody($render);
    }
    
    private function loadForm() {
        return include \ROOT . '/Src/form.php';
    }
}
