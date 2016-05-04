<?php

error_reporting(E_ALL);

/**
 * Функция получает список файлов в папке по расширению.
 *
 * @param $relPath относительный путь к папке
 * @param $ext расширение файла
 *
 * @return array массив имен файлов
 */
function getFiles($relPath, $ext) {

    if(!is_dir($relPath)) {
        return [];
    }

    chdir($relPath);

    $files = [];
    $fileName = "1.{$ext}";

    while (is_file($fileName)) {
        array_push($files, $relPath . DIRECTORY_SEPARATOR . $fileName);
        $fileName[0] = (int)$fileName[0] + 1;
    }

    chdir(__DIR__);
    return $files;
}

/**
 * Функция собирает информацию о файлах: размер и время изменения
 *
 * @param $files
 *
 * @return array
 */
function getFilesInfo($files) {
    $info = [];

    foreach ( $files as $file ) {
        $info[$file]['size']  = filesize($file);
        $info[$file]['mtime'] = filemtime($file);
    }
    
    return $info;
}

/**
 * Функция экпортирует информацию о файлах в формат csv
 *
 * @param $filesInfo массив данных
 * @param $fileName имя файла для экспорта
 *
 * @return bool
 */
function exportToCsv($filesInfo, $fileName) {

    if (!is_writable(realpath('.'))) {
        return false;
    }

    $fd = fopen($fileName, 'w');

    foreach ($filesInfo as $fname => $info) {
        array_unshift($info, $fname);
        fputcsv($fd, $info);
    }

    fclose($fd);

    return filesize($fileName);
}

/**
 * Функция создает превью для изображений.
 *
 * @param $files массив имен файлов
 *
 * @param $dir каталог для превью
 * @param $suffix суффикс для файлов
 * @param $width максимальная ширина превью
 *
 * @return array пути созданных превью
 */
function buildPreview($files, $suffix, $width) {

    $previews = [];

    foreach ( $files as $filename ) {

        $pathParts = pathinfo($filename);
        $newName  = $pathParts['dirname'] . DIRECTORY_SEPARATOR;
        $newName .= $pathParts['filename'] . $suffix . '.' . $pathParts['extension'];

        // получение новых размеров
        list($widthOrig, $heightOrig) = getimagesize($filename);

        $ratioOrig = $widthOrig/$heightOrig;

        $height = $width/$ratioOrig;

        // ресэмплирование
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $widthOrig, $heightOrig);

        // вывод
        if (imagejpeg($image_p, $newName, 100)) {
            $previews[$filename]['preview'] = $newName;
        }
    }

    return $previews;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Gallery Task | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body class="container text-center">

<?php
    $dir     = 'img';
    $ext     = 'jpg';
    $csvname = 'data.csv';
    $width   = 200;
    $suffix  = '_small';

    $files = getFiles($dir, $ext);

    if(!empty($files)) {

        echo "<p>В каталоге $dir найдены изображения в формате $ext.</p>";
        if ($info  = getFilesInfo($files)) {
            echo '<p>Информация о файлах собрана успешно.</p>';
        } else {
            echo '<p>Ошибка сбора информации о файлах.</p>';
        }
        if ($previews = buildPreview(array_keys($info), $suffix, $width)) {
            echo '<p>Превью созданы успешно.</p>';
            $info = array_merge_recursive($info, $previews);
        } else {
            echo '<p>Превью не созданы.</p>';
        }
        if (exportToCsv($info, $csvname)) {
            echo '<p>Информация о файлах успешно экспортирована в CSV.</p>';
        } else {
            echo '<p>Ошибка экспорта в формат CSV.</p>';
        }
        echo '<p><a href="show.php" target="_blank">перейти к списку файлов</a></p>';

    } else {
        echo "<p>В каталоге $dir не найдены изображения в формате $ext.</p>";
    }
?>

</body>
</html>
