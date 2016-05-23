<?php

error_reporting(E_ALL);

define('ROOT', __DIR__);

require ROOT . '/vendor/autoload.php';

use App\Application;
use App\Http\Request;
use App\Http\Response;

$app = new Application;
$request = new Request();
$response = new Response();
$app->execute($request, $response);
