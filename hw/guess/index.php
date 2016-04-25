<?php

error_reporting(E_ALL);

/**
 * Функция возвращает случайное целое число от 1 до 100
 * и сохраняет его в файл.
 *
 * @param bool $reset Флаг удаления файла с числом для новой попытки.
 *
 * @return bool|int Загаданное компьютером число.
 */
function getNumberA($reset = false)
{
    $fileName = 'pcnumber.txt';
    
    if ($reset) {
        return unlink($fileName);
    }
    
    if (is_readable($fileName)) {
        return intval(file_get_contents($fileName));
    } else {
        if (is_writable(realpath('.'))) {
            $num = rand(1, 100);
            file_put_contents($fileName, $num);
            return $num;
        }
    }
    return 0;
}

/**
 * Функция возвращает число, которое ввёл игрок.
 *
 * @return int
 */
function getNumberB()
{
    if (isset($_GET['b'])) {
        return intval($_GET['b']);
    }
    return NAN;
}

$pcNumA     = getNumberA();
$playerNumB = getNumberB();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Guess Number Task | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style>
        p:first-child { margin-top: 200px; }
    </style>
</head>
<body class="container text-center lead">

    <?php
        if (is_nan($playerNumB)) {
            echo '<p >Передайте ваше число через параметр <code>GET: http://site/?b=<число></code></p>';
        } else {
            if ($playerNumB > $pcNumA) {
                echo '<p class="bg-danger">много</p>';
                echo '<p>попробуйте число поменьше</p>';
            } else {
                if ($playerNumB < $pcNumA) {
                    echo '<p class="bg-info">мало</p>';
                    echo '<p>попробуйте число побольше</p>';
                } else {
                    echo '<p class="bg-success">вы угадали</p>';
                    echo '<p>попробуйте еще</p>';
                    getNumberA(true);
                }
            }
        }
    ?>

</body>
</html>
