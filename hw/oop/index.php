<?php

error_reporting(E_ALL);

define('ROOT', __DIR__);

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

require ROOT . '/vendor/autoload.php';

use App\Application;
use App\Http\Requests\Request;
use App\Http\Responses\Response;

$app = Application::getInstance();
$request  = Request::build();
$response = Response::build($request);
$app->execute($request, $response);

