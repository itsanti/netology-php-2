<?php

error_reporting(E_ALL);

session_start();

$isAdmin = false;
$login   = !empty($_POST['login']) ? trim(xssafe($_POST['login'])) : false;

if ($login and !empty($_POST['pass'])) {
    $isAdmin = isAdmin($login, trim($_POST['pass']));
    if ($isAdmin) {
        $_SESSION['login'] = $login;
        $_SESSION['isAdmin'] = true;
        $_SESSION['name'] = $isAdmin;
    } else {
        header('Location: index.php', true, 303);
        exit(1);
    }
} elseif ($login) {
    $_SESSION['login'] = $login;
    $_SESSION['name']  = $login;
    header('Location: index.php', true, 303);
    exit;
}

// Выход из системы
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php', true, 303);
    exit;
}

if (isset($_SESSION['login'])) {
    $login = $_SESSION['login'];
}
if (isset($_SESSION['isAdmin'])) {
    $isAdmin = $_SESSION['name'];
}

/**
 * Функция проверяет является ли пользователь администратором.
 * Если да, то возвращает его имя.
 *
 * @param string $login имя пользователя
 * @param string $pass пароль пользователя
 *
 * @return bool
 */
function isAdmin($login, $pass)
{
    $file = __DIR__ . '/users.json';

    if (is_readable($file)) {
        $data = json_decode(file_get_contents($file), true);
        foreach ( $data as $user ) {
            if ($user['login'] === $login && password_verify($pass, $user['pswd'])) {
                return $user['name'];
            }
        }
    }

    return false;
}

function xssafe($data, $encoding='UTF-8')
{
    return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Test Generator | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body class="container">
    <div class="jumbotron">
        <h1>Генератор тестов на PHP и JSON</h1>
        <p>выберите действие из меню</p>
    </div>
    <?php if ($login): ?>
        <p>Привет, <?php echo $_SESSION['name']; ?>.</p>
        <form action="index.php" method="post">
            <button type="submit" class="btn btn-danger" name="logout">выйти</button>
        </form>
    <?php else: ?>
        <p>Привет, Гость. Войдите в систему, чтобы получить сертификат.</p>
        <form action="index.php" method="post" style="border: 1px solid #bbb; width:25%;padding:10px;margin-bottom: 20px;">
            <div class="form-group">
                <label for="login">Логин / Имя</label>
                <input type="text" id="login" name="login" required placeholder="login">
                <p class="help-block">введите логин или имя, чтобы войти как <strong>Гость</strong></p>
            </div>
            <div class="form-group">
                <label for="pass">Пароль</label>
                <input type="password" id="pass" name="pass" placeholder="password">
                <p class="help-block">введите пароль администратора, для <strong>Гостя</strong> пароль не нужен</p>
            </div>
            <button type="submit" class="btn btn-success">войти</button>
        </form>
    <?php endif; ?>
    <ul class="nav nav-pills nav-stacked">
        <?php if ($isAdmin): ?>
            <li><a href="admin.php">загрузить JSON файл c тестом</a></li>
        <?php endif; ?>
        <li><a href="list.php">список загруженных тестов</a></li>
    </ul>
</body>
</html>
