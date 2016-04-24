<?php
$name = 'Александр';
$age  = 32;
// Email example. Please contact with me from netology user profile.
$email = 'aleksandr@netology.loc';
$city  = 'Санкт-Петербург';
$info  = 'начинающий PHP-разработчк';
?>
<!DOCTYPE>
<html lang="ru">
<head>
    <title><?= $name; ?> | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>
        .col-lg-2, .col-lg-3 { padding: 0; }
    </style>
</head>
<body class="container">
    <div class="page-header text-center">
        <h1>Страница пользователя <code><?= $name; ?></code></h1>
    </div>
    <div class="row">
        <dl class="panel panel-success col-lg-2">
            <dt class="panel-heading">
                <span class="glyphicon glyphicon-user"></span> Имя
            </dt>
            <dd class="panel-body"><?= $name; ?></dd>
        </dl>
        <dl class="panel panel-success col-lg-2 col-lg-offset-1">
            <dt class="panel-heading">
                <span class="glyphicon glyphicon-signal"></span> Возраст
            </dt>
            <dd class="panel-body"><?= $age; ?></dd>
        </dl>
        <dl class="panel panel-default col-lg-3 col-lg-offset-1">
            <dt class="panel-heading">
                <span class="glyphicon glyphicon-envelope"></span> Адрес электронной почты
            </dt>
            <dd class="panel-body"><a href="mailto:<?= $email; ?>"><?= $email; ?></a></dd>
        </dl>
        <dl class="panel panel-default col-lg-2 col-lg-offset-1">
            <dt class="panel-heading">
                <span class="glyphicon glyphicon-globe"></span> Город
            </dt>
            <dd class="panel-body"><?= $city; ?></dd>
        </dl>
    </div>
    <div class="row">
        <dl class="panel panel-info">
            <dt class="panel-heading">
                <span class="glyphicon glyphicon-info-sign"></span> О себе
            </dt>
            <dd class="panel-body"><?= $info; ?></dd>
        </dl>
    </div>
</body>
</html>
