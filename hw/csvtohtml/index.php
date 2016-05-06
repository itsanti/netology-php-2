<?php

error_reporting(E_ALL);

/**
 * Функция импортирует данные из CSV
 *
 * @param $filename string имя файла с данными CSV
 * @param $delim string символ разделитель для CSV
 *
 * @return array|bool массив данных
 */
function getData($filename, $delim) {

    if (!is_readable($filename)) {
        return false;
    }

    $data = [];

    $fd = fopen($filename, 'r');

    while (($str = fgetcsv($fd, 1000, $delim)) !== FALSE) {
        $data[] = $str;
    }

    fclose($fd);

    if (empty($data)) {
        return false;
    }

    return $data;
}

/**
 * Функция возвращает разметку для данных в виде таблицы
 *
 * @param $data array информация о файлах
 * @param $header bool есть строка заголовка или нет
 *
 * @return string html разметка
 */
function renderTable($data, $header) {

    $html  = '<table class="table table-striped table-bordered table-condensed">';

    if ($header) {
        $ths   = array_shift($data);
        $html .= renderTableRow($ths, 'th');
    }

    foreach ( $data as $row ) {
        $html .= renderTableRow($row, 'td');
    }

    $html .= '</table>';
    return $html;
}

/**
 * Вспомогательная функция для рендеринга строки таблицы
 *
 * @param $cells array массив ячеек строки
 * @param $cellType string тип ячейки
 *
 * @return string html разметка строки
 */
function renderTableRow($cells, $cellType) {
    $html  = '<tr>';
    foreach ( $cells as $cell ) {
        $html .= "<{$cellType}>{$cell}</{$cellType}>";
    }
    $html .= '</tr>';
    return $html;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>CSV To HTML | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>
        .table > tbody > tr > td,
        .table > tbody > tr > th { vertical-align: middle; }
        th {text-align: center; background-color: #d9edf7; }
    </style>
</head>
<body class="container">
<?php

    $fileName = 'data.csv';
    $title    = 'Бесплатные зоны Wi-Fi в общественных местах';

    if (($data = getData($fileName, ';')) === false) {
        echo '<p>Нет данных для отображения.</p>';
    } else {
        echo "<h1 class=\"text-center\">{$title}</h1>";
        echo renderTable($data, true);
        echo '<p class="text-center"><a href="#" class="btn btn-info btn-sm">наверх</a></p>';
    }
?>
</body>
</html>