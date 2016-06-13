<?php

error_reporting(E_ALL);

define('TPLS', __DIR__ . '/Templates/');

require  __DIR__ . '/vendor/autoload.php';

use App\HW2\ToDoApplication;

$app = ToDoApplication::getInstance();
$app->execute();
