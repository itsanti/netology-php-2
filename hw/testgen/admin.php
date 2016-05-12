<?php

error_reporting(E_ALL);

if (array_key_exists('uptest', $_FILES)) {

    $file  = $_FILES['uptest'];

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        $error = '<p class="text-danger">Выберите файл для загрузки.</p>';
    } elseif (!extCheck($file['name'], ['json'])) {
        $error = '<p class="text-danger">Допускаются файлы с расширением <strong>json</strong>.</p>';
    } elseif (!mimeCheck($file['tmp_name'], 'text/plain')) {
        $error = '<p class="text-danger">Вы пытаетесь загрузить не JSON.</p>';
    } else {
        $newName = __DIR__ . '/uploads/test_' . time() . '.json';
        if (!move_uploaded_file($file['tmp_name'], $newName)) {
            $error = '<p class="text-danger">Что-то пошло не так. Попробуйте позже.</p>';
        } else {
            $error = '<p class="text-success">Файл успешно загружен на сервер.</p>';
        }
    }

}

/**
 * Функция проверяет расширение файла.
 *
 * @param string $fileName путь к файлу
 * @param array $ext разрешенные расширения файла
 *
 * @return bool
 */
function extCheck($fileName, $ext) {
    return in_array(pathinfo($fileName, PATHINFO_EXTENSION), $ext);
}

/**
 * Функция проверяет MIME-тип файла.
 *
 * @param string $fileName путь к файлу
 * @param string $mime разрешенный mime-тип
 *
 * @return bool совпадает тип или нет
 */
function mimeCheck($fileName, $mime)
{
    $finfo   = finfo_open(FILEINFO_MIME_TYPE);
    $mimeTmp = finfo_file($finfo, $fileName);
    finfo_close($finfo);

    return $mimeTmp === $mime;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Upload Test | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body class="container">
<nav class="navbar navbar-default">
    <ul class="nav navbar-nav">
        <li><a href="index.php">на главную</a></li>
        <li class="active"><a href="admin.php">admin</a></li>
        <li><a href="list.php">list</a></li>
        <li><a href="test.php">test</a></li>
    </ul>
</nav>
    <?php if(isset($error)): ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo $error; ?>
            </div>
        </div>
    <?php endif; ?>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="uptest">JSON файл с тестом</label>
            <input type="file" id="uptest" name="uptest" required>
            <p class="help-block">тест в формате json</p>
        </div>
        <button type="submit" class="btn btn-success">загрузить тест</button>
    </form>
</body>
</html>