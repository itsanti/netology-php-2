<?php

error_reporting(E_ALL);

define('TPLS', __DIR__ . '/Templates/');

require  __DIR__ . '/vendor/autoload.php';

use App\HW4\ShowTableApplication;

$app = ShowTableApplication::getInstance();
$app->execute();
