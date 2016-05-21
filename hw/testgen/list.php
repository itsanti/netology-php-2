<?php

error_reporting(E_ALL);

session_start();

$msg = null;
$content = '';

if (!empty($_SESSION['isAdmin']) && isset($_POST['del'])) {
    delTest($_POST['ts'], $_POST['id']);
    header('Location: list.php', true, 303);
    exit;
}

if (isset($_GET['ok'])) {
    $msg = '<p class="text-success">Файл успешно загружен на сервер.</p>';
}

if (!empty($_SESSION['isAdmin']) && isset($_GET['act']) && $_GET['act'] === 'del') {
    $ts = (!empty($_GET['ts'])) ? intval($_GET['ts']) : null;
    $id = (!empty($_GET['id'])) ? intval($_GET['id']) : null;
    $content =<<<HTML
    <p>Вы точно хотите удалить тест №$id из файла <code>test_$ts.json</code>?</p>
<form action="list.php" method="post">
    <input type="hidden" name="ts" value="$ts">
    <input type="hidden" name="id" value="$id">
    <button type="submit" class="btn btn-danger" name="del">удалить тест</button>
</form>
HTML;
} else {
    $content = renderTests(importTests());
}

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
 * Функция удаляет тест из файла и файл, если тест был единственным.
 * 
 * @param number $ts временная метка файла
 * @param number $id идентификатор теста  
 *
 * @return bool
 */
function delTest($ts, $id)
{
    $file = __DIR__ . '/uploads/test_'.  $ts . '.json';

    if (is_writable($file)) {
        $data = json_decode(file_get_contents($file), true);
        foreach ( $data as $key => $test ) {
            if ($test['id'] == $id) {
                unset($data[$key]);
            }
        }
        if (empty($data)) {
            unlink($file);
        } else {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT);
            file_put_contents($file, $data);
        }
        return true;
    }
    return false;
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
    $isAdmin = false;

    if (!empty($_SESSION['isAdmin'])) {
        $isAdmin = true;
    }

    $html  = '<h1>Доступные тесты</h1>';
    $html .= '<table class="table table-bordered table-condensed text-center">';
    $html .= '<th>#</th><th>вопрос</th><th>дата публикации</th><th>перейти к тесту</th>';
    if ($isAdmin) {
        $html .= '<th>удалить тест</th>';
    }
    $html .= '</tr>';

    foreach ( $tests as $test ) {
        $test['id'] = intval($test['id']);
        $test['q']  = trim(xssafe($test['q']));

        $html .= "<tr><td>{$test['id']}</td><td class=\"text-left\">{$test['q']}</td><td>{$test['datetime']}</td>";
        $html .= "<td><a href=\"test.php?ts={$test['timestamp']}&id={$test['id']}\"" .
                 " class=\"btn btn-success\">открыть тест</a></td>";
        if ($isAdmin) {
            $html .= "<td><a href=\"list.php?act=del&ts={$test['timestamp']}&id={$test['id']}\"" .
                     " class=\"btn btn-danger\">удалить тест</a></td>";
        }
        $html .= '</tr>';
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
<?php if(isset($msg)): ?>
<div class="panel panel-default">
    <div class="panel-body">
        <?php echo $msg; ?>
    </div>
</div>
<?php endif; ?>
<section>
    <?php
        echo $content;
    ?>
</section>
</body>
</html>