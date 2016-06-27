<?php

error_reporting(E_ALL);
require  __DIR__ . '/vendor/autoload.php';

$query = !empty($_GET['q']) ? xssafe($_GET['q']) : '';
$response = '';

if (!empty($query)) {
    $response = renderResponse(getResponse($query));
}

echo renderPage(renderForm($query) . $response);

/**
 * Функция получает ответ от сервиса Yandex/Geo.
 *
 * @param string $query адрес
 *
 * @return \Yandex\Geo\GeoObject[]
 * @throws \Yandex\Geo\Exception
 * @throws \Yandex\Geo\Exception\CurlError
 * @throws \Yandex\Geo\Exception\ServerError
 */
function getResponse($query)
{
    $api = new \Yandex\Geo\Api();
    $api->setQuery($query);
    $api->load();
    $response = $api->getResponse();
    return $response->getList();
}

/**
 * Функция формирует разметку для таблицы результатов.
 *
 * @param \Yandex\Geo\GeoObject[] $items данные для отображения.
 *
 * @return string HTML разметка для таблицы
 */
function renderResponse($items)
{
    if (empty($items)) {
        return '<p class="alert alert-danger">По вашему запросу ничего не найдено.</p>';
    }

    $html =<<<HTML
<table class="table table-bordered table-condensed">
<tr>
    <th class="text-center bg-success">Адрес</th>
    <th class="text-center bg-info">Широта</th>
    <th class="text-center bg-warning">Долгота</th>
</tr>
HTML;
    foreach ($items as $item) {
        $html .= "<tr><td style=\"width: 70%;\">{$item->getAddress()}</td>" .
                 "<td>{$item->getLatitude()}</td><td>{$item->getLongitude()}</td></tr>";
    }
    $html .= '</table>';
    return $html;
}

/**
 * Функция формирует разметку для формы ввода адреса.
 *
 * @param string $value запрос к сервису Yandex (адрес)
 *
 * @return string HTML разметка для формы
 */
function renderForm($value)
{
    $html =<<<HTML
<form action="" method="get" class="form-inline" style="padding-bottom: 10px;">
    <div class="form-group col-sm-4">
        <input class="form-control" type="text" name="q" placeholder="Адрес" value="$value" style="width: 350px;">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success">Найти</button>
    </div>
</form>
HTML;
    return $html;
}

/**
 * Функция формирует страницу приложения.
 *
 * @param string $content содержимое страницы
 * @return string html разметка
 */
function renderPage($content)
{
    $tpl = file_get_contents('index.html');
    return str_replace("{{content}}", $content, $tpl);
}

/**
 * Функция защиты от XSS.
 */
function xssafe($data, $encoding='UTF-8')
{
    return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
}