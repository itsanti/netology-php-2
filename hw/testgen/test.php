<?php

error_reporting(E_ALL);

$content = '<p>Тест не выбран. Вернитесь на страницу со <a href="list.php">списком тестов</a>.</p>';
$answer  = null;

if (isset($_GET['ts']) and isset($_GET['id'])) {

    $ts = intval($_GET['ts']);
    $id = intval($_GET['id']);
    
    $test = getTest($ts, $id);        

    if (!empty($test)) {
        if (isset($_POST['a'])) {
            $answer    = mb_convert_case(xssafe(trim($_POST['a'])), MB_CASE_LOWER, 'UTF-8');
            $test['a'] = mb_convert_case(xssafe(trim($test['a'])), MB_CASE_LOWER, 'UTF-8');
            if ($answer === $test['a']) {
                $answer  = "<p class=\"text-success\">Ваш ответ <kbd>{$answer}</kbd> правильный.<br>";
                $answer .= 'Попробуйте ответить на <a href="list.php">другой вопрос</a>.</p>';
            } else {
                $answer  = "<p class=\"text-danger\">Ваш ответ <kbd>{$answer}</kbd> неверный.";
                $answer .= " Попробуйте еще раз.</p>";
            }
        }
        $content = renderTest($test, $ts, $id);
    }
}

/**
 * Функция получает данные теста
 *
 * @param int $ts метка времени файла с тестом
 * @param int $id номер теста в файле
 *
 * @return array массив с тестом
 */
function getTest($ts, $id)
{
    $file = __DIR__ . '/uploads/test_' . $ts . '.json';

    if (is_readable($file)) {
        $data = json_decode(file_get_contents($file), true);
        foreach ( $data as $test ) {
            if ($test['id'] == $id) {
                return $test;
            }
        }
    }

    return [];
}

/**
 * Функция возвращает разметку формы для теста.
 *
 * @param array $test информация о тесте
 *
 * @param int $ts метка времени файла с тестом
 * @param int $id номер теста в файле
 *
 * @return string html разметка
 */
function renderTest($test, $ts, $id)
{
    $test['q'] = trim(xssafe($test['q']));

    $html  = '<h1>Ответьте на вопрос</h1>';
    $html .=<<<HTML
<form action="test.php?ts=$ts&id=$id" method="post">
    <div class="form-group">
        <label for="a" class="well">{$test['q']}</label>
        <div class="row">
            <div class="col-xs-3">
                <input class="form-control input-lg" type="text" id="a" name="a" required placeholder="ваш ответ…">
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success">проверить</button>
    </div>
</form>
HTML;
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
    <title>Test | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body class="container">
<nav class="navbar navbar-default">
    <ul class="nav navbar-nav">
        <li><a href="index.php">на главную</a></li>
        <li><a href="admin.php">admin</a></li>
        <li><a href="list.php">list</a></li>
        <li class="active"><a href="test.php">test</a></li>
    </ul>
</nav>
<?php if(isset($answer)): ?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php echo $answer; ?>
        </div>
    </div>
<?php endif; ?>
<?php
    echo $content;
?>
</body>
</html>
