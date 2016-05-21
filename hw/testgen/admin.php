<?php

error_reporting(E_ALL);

session_start();

$isAdmin = false;

if (empty($_SESSION['isAdmin'])) {
    http_response_code(403);
} else {
    $isAdmin = $_SESSION['isAdmin'];
}

if (array_key_exists('uptest', $_FILES) and $isAdmin) {

    $file  = $_FILES['uptest'];

    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        $error = '<p class="text-danger">Выберите файл для загрузки.</p>';
    } elseif (!extCheck($file['name'], ['json'])) {
        $error = '<p class="text-danger">Допускаются файлы с расширением <strong>json</strong>.</p>';
    } elseif (!mimeCheck($file['tmp_name'], 'text/plain')) {
        $error = '<p class="text-danger">Вы пытаетесь загрузить не JSON.</p>';
    } elseif (!formatCheck($file['tmp_name'])) {
        $error = '<p class="text-danger">Неверный формат теста.</p>';
    } else {
        $newName = __DIR__ . '/uploads/test_' . time() . '.json';
        if (!move_uploaded_file($file['tmp_name'], $newName)) {
            $error = '<p class="text-danger">Что-то пошло не так. Попробуйте позже.</p>';
        } else {
            header('Location: list.php?ok', true, 303);
            exit;
        }
    }

}

/**
 * Функция проверяет формат теста.
 *
 * @param string $fileName путь к файлу
 *
 * @return bool в правильном формате json или нет
 */
function formatCheck($fileName) {
    $json = file_get_contents($fileName);
    $json = json_decode($json, true);

    if (!is_null($json)) {
        if (isset($json[0])) {
            $needKeys = ['id', 'q', 'a'];
            foreach ( $json as $test ) {
                if (count(array_intersect_key(array_flip($needKeys), $test)) !== count($needKeys)) {
                    return false;
                }
            }
            return true;
        }
    }
    return false;
}

/**
 * Функция проверяет расширение файла.
 *
 * @param string $fileName путь к файлу
 * @param array $ext разрешенные расширения файла
 *
 * @return bool соответствует ли расширение разрешенному
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
    <?php if ($isAdmin): ?>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="uptest">JSON файл с тестом</label>
            <input type="file" id="uptest" name="uptest" required>
            <p class="help-block">тест в формате json</p>
        </div>
        <button type="submit" class="btn btn-success">загрузить тест</button>
    </form>
    <div>
        <h2>Формат теста</h2>
        <pre>
            [
                {
                    // int уникальный номер теста в файле
                    "id": 1,
                    // string вопрос
                    "q": "2 + 2",
                    // string | int  ответ
                    "a": 4
                }
                [, { ... }, ...]
            ]
        </pre>
    </div>
    <?php else: ?>
        <p>У вас нет доступа к администрированию тестов.</p>
    <?php endif; ?>
</body>
</html>