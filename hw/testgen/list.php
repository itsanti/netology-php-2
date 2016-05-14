<?php

error_reporting(E_ALL);

/**
 * Функция импортирует данные о тестах
 *
 * @return array
 */
function importTests() {
    $files = glob(__DIR__ . "/uploads/test_*.json");
    $tests = [];
    foreach ( $files as $file ) {
        $data = json_decode(file_get_contents($file), true);
        $time = getTime($file);
        foreach ( $data as $test ) {
            $tests[] = $test + $time;
        }
    }
    return $tests;
}

/**
 * Функция возвращает информацию о времени загрузки файла.
 *
 * @param string $data имя файла
 *
 * @return array массив с меткой времени и форматированной датой
 */
function getTime($data)
{
    preg_match('~\d+~', basename($data), $match);

    $time = [
        'timestamp' => $match[0],
        'datetime' => date('Y-m-d H:m:s', $match[0])
    ];

    return $time;
}

/**
 * Функция возвращает разметку для тестов.
 *
 * @param array $tests информация о тестах
 *
 * @return string html разметка
 */
function renderTests($tests)
{
    $html  = '<h1>Доступные тесты</h1>';
    $html .= '<table class="table table-bordered table-condensed text-center">';
    $html .= '<th>#</th><th>вопрос</th><th>дата публикации</th><th>перейти к тесту</th></tr>';

    foreach ( $tests as $test ) {
        $test['id'] = intval($test['id']);
        $test['q']  = trim(xssafe($test['q']));

        $html .= "<tr><td>{$test['id']}</td><td class=\"text-left\">{$test['q']}</td><td>{$test['datetime']}</td>";
        $html .= "<td><a href=\"test.php?ts={$test['timestamp']}&id={$test['id']}\"" .
                 " class=\"btn btn-success\">открыть тест</a></td></tr>";
    }

    $html .= '</table>';
    return $html;
}

function xssafe($data, $encoding='UTF-8')
{
    return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Show Tests | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>
        .table > tbody > tr > td { vertical-align: middle; }
        th {text-align: center;}
    </style>
</head>
<body class="container">
<nav class="navbar navbar-default">
    <ul class="nav navbar-nav">
        <li><a href="index.php">на главную</a></li>
        <li><a href="admin.php">admin</a></li>
        <li class="active"><a href="list.php">list</a></li>
        <li><a href="test.php">test</a></li>
    </ul>
</nav>
<section>
    <?php
        echo renderTests(importTests());
    ?>
</section>
</body>
</html>