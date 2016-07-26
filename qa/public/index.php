<?php

require __DIR__ . '/../boot.php';
require __DIR__ . '/../vendor/autoload.php';

use App\Application;
use App\Http\Requests\Request;
use App\Http\Responses\Response;

$app = Application::getInstance();
$request  = Request::build();
$response = Response::build($request);
$app->run($request, $response);
