<?php

error_reporting(E_ALL);

define('ROOT', __DIR__);

require ROOT . '/vendor/autoload.php';

use App\Application;

$app = Application::getInstance();
$app->execute();
