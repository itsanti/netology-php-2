<?php

error_reporting(E_ALL);

/**
 * Функция импортирует информацию о файлах из CSV
 *
 * @param $relPath
 *
 * @return array|bool
 */
function getFilesInfo($relPath) {

    if (!is_readable($relPath)) {
        return false;
    }

    $info = [];

    $fd = fopen($relPath, 'r');

    while (($data = fgetcsv($fd, 1000, ',')) !== FALSE) {
        $info[$data[0]]['size']    = $data[1];
        $info[$data[0]]['mtime']   = $data[2];
        $info[$data[0]]['preview'] = $data[3];
    }

    fclose($fd);

    return $info;
}

/**
 * Функция возвращает разметку для галереи.
 *
 * @param $info информация о файлах
 *
 * @return string html разметка
 */
function renderGallery($info) {
    $html  = '<h1>галерея автомобилей</h1>';
    $html .= '<table class="table table-bordered table-condensed text-center">';
    $html .= '<tr><th>превью</th><th>размер оригинала в байтах</th><th>время изменения</th>';
    $html .= '<th>открыть в полном размере</th></tr>';

    foreach ( $info as $fname => $image ) {
        $mtime = date('Y-m-d H:m:s', $image['mtime']);
        if (!file_exists($image['preview'])) {
            $image['preview'] = 'default.png';
        }
        $html .= "<tr><td><img class=\"img-thumbnail\" src=\"{$image['preview']}\"></td>";
        $html .= "<td>{$image['size']}</td><td>{$mtime}</td>";
        $html .= "<td><a href=\"$fname\" class=\"btn btn-success\" target=\"_blank\">открыть</a></td></tr>";
    }

    $html .= '</table>';
    return $html;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Gallery Show Page | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>
        .table > tbody > tr > td { vertical-align: middle; }
        th {text-align: center;}
    </style>
</head>
<body class="container text-center">

<?php
    $fileName = 'data.csv';

    if (($info = getFilesInfo($fileName)) === FALSE) {
        echo '<p>Нет информации об изображениях.</p>';
    }

    echo renderGallery($info);
?>
</body>
</html>
