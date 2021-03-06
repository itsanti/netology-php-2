<?php

error_reporting(E_ALL);

// Для России массив нужен из 3-х уровней.
$regions = [
    'республика' => [
        'Адыгея' => ['Майкоп', 'Адыгейск'],
        'Алтай'  => ['Горно-Алтайск'],
        'Башкортостан' => ['Агидель', 'Баймак', 'Белебей', 'Белорецк', 'Бирск',
            'Благовещенск', 'Давлеканово', 'Дюртюли', 'Ишимбай', 'Кумертау',
            'Межгорье', 'Мелеуз', 'Нефтекамск', 'Октябрьский', 'Салават', 'Сибай',
            'Стерлитамак', 'Туймазы', 'Уфа', 'Учалы', 'Янаул'],
        'Бурятия' => ['Улан-Удэ', 'Северобайкальск'],
        'Дагестан' => ['Буйнакск', 'Дагестанские Огни', 'Дербент', 'Избербаш',
            'Каспийск', 'Кизилюрт', 'Кизляр', 'Махачкала', 'Хасавюрт', 'Южно-Сухокумск'],
        'Ингушетия' => ['Карабулак', 'Магас', 'Малгобек', 'Назрань'],
        'Кабардино-Балкария' => ['Нальчик', 'Баксан', 'Прохладный'],
        'Калмыкия' => ['Элиста'],
        'Карачаево-Черкесия' => ['Черкесск', 'Карачаевск'],
        'Карелия' => ['Беломорск', 'Кемь', 'Кондопога', 'Лахденпохья', 'Медвежьегорск',
            'Олонец', 'Питкяранта', 'Петрозаводск', 'Пудож', 'Сегежа', 'Сортавала', 'Суоярви'],
        'Коми' => ['Воркута', 'Вуктыл', 'Емва', 'Печора', 'Сосногорск', 'Микунь', 'Сыктывкар',
            'Инта', 'Усинск', 'Ухта'],
        'Крым' => ['Алупка', 'Алушта', 'Армянск', 'Бахчисарай', 'Белогорск', 'Джанкой',
            'Евпатория', 'Керчь', 'Красноперекопск', 'Саки', 'Симферополь', 'Старый Крым',
            'Судак', 'Феодосия', 'Щёлкино', 'Ялта'],
        'Марий Эл' => ['Йошкар-Ола', 'Волжск', 'Козьмодемьянск'],
        'Мордовия' => ['Ардатов', 'Инсар', 'Ковылкино', 'Краснослободск', 'Рузаевка', 'Темников'],
        'Саха' => ['Якутск', 'Алдан', 'Вилюйск', 'Ленск', 'Мирный', 'Нерюнгри', 'Нюрба', 'Олёкминск',
            'Среднеколымск', 'Покровск'],
        'Северная Осетия-Алания' => ['Владикавказ'],
        'Татарстан' => ['Азнакаево', 'Альметьевск', 'Бавлы', 'Бугульма', 'Буинск', 'Елабуга',
            'Заинск', 'Зеленодольск', 'Казань', 'Лениногорск', 'Набережные Челны',
            'Нижнекамск', 'Нурлат', 'Чистополь'],
        'Тыва' => ['Кызыл', 'Ак-Довурак'],
        'Удмуртия' => ['Ижевск', 'Воткинск', 'Глазов', 'Можга', 'Сарапул'],
        'Хакасия' => ['Абакан', 'Абаза', 'Саяногорск', 'Сорск', 'Черногорск'],
        'Чечня' => ['Грозный', 'Аргун', 'Гудермес', 'Урус-Мартан', 'Шали'],
        'Чувашия' => ['Чебоксары', 'Алатырь', 'Канаш', 'Козловка', 'Мариинский Посад',
            'Цивильск', 'Шумерля', 'Ядрин']
    ],
    'край' => [
        'Алтайский край' => ['Барнаул', 'Алейск', 'Белокуриха', 'Бийск', 'Заринск',
            'Новоалтайск', 'Рубцовск', 'Славгород', 'Яровое'],
        'Забайкальский край' => ['Балей', 'Борзя', 'Краснокаменск', 'Могоча',
            'Нерчинск', 'Сретенск', 'Хилок', 'Чита', 'Шилка'],
        'Камчатский край' => ['Петропавловск-Камчатский', '	Вилючинский'],
        'Краснодарский край' => ['Краснодар', 'Анапа', 'Армавир', 'Геленджик', 'Горячий Ключ',
            'Новороссийск', 'Сочи'],
        'Красноярский край' => ['Красноярск', 'Ачинск', 'Боготол', 'Бородино', 'Дивногорск'],
        'Пермский край' => ['Пермь', 'Березники', 'Кудымкар', 'Кунгур', 'Соликамск'],
        'Приморский край' => ['Владивосток', 'Арсеньев', 'Артём', 'Иман', 'Лесозаводск',
            'Находка', 'Спасск-Дальний', 'Сучан', 'Уссурийск'],
        'Ставропольский край' => ['Ставрополь', 'Георгиевск', 'Ессентуки', 'Железноводск',
            'Кисловодск', 'Лермонтов', 'Невинномысск', 'Пятигорск'],
        'Хабаровский край' => ['Хабаровск', 'Благовещенск', 'Владивосток',
            'Александровск', 'Сретенск', 'Хабаровск', 'Чита']
    ],
    /*'область' => [
        'области' => []
    ],*/
    'город федерального значения' => [
        'Москва' => ['Москва'],
        'Санкт-Петербург' => ['Санкт-Петербург'],
        'Севастополь' => ['Севастополь']
    ],
    'автономная область' => [
        'Еврейская АО' => ['Биробиджан']
    ],
    'автономный округ' => [
        'Ненецкий АО' => ['Нарьян-Мар'],
        'Ханты-Мансийский АО' => ['Ханты-Мансийск', 'Когалым', 'Лангепас', 'Мегион',
            'Пыть-Ях', 'Радужный', 'Югорск'],
        'Чукотский АО' => ['Анадырь'],
        'Ямало-Ненецкий АО' => ['Салехард', 'Губкинский', 'Лабытнанги', 'Муравленко',
            'Новый Уренгой', 'Ноябрьск']
    ]
];

/**
 * Функция находит города из двух слов.
 *
 * @param $regions исходный массив данных
 *
 * @return array массив городов
 */
function towWordsCities($regions) {
    $result = [];
    foreach ( $regions as $subjects ) {
        foreach ( $subjects as $region => $cities ) {
            foreach ( $cities as $city ) {
                if (strpos($city, '-') or strpos($city, ' ')) {
                    $result[$region][] = $city;
                }
            }
        }
    }
    return $result;
}

/**
 * Функция случайным образом составляет имена городов
 * исходный регион сохраняется по первой части названия.
 *
 * @param $cities реальные города
 *
 * @return array фантазийные города
 */
function randomize($cities) {
    $left     = [];
    $right    = [];
    $newNames = [];
    $result   = [];

    foreach ( $cities as $region => $city ) {
        foreach ( $city as $name ) {
            if (strpos($name, '-')) {
                $parts  = explode('-', $name);
                $left[] = "$region|{$parts[0]}-";
            } else {
                $parts  = explode(' ', $name);
                $left[] = "$region|{$parts[0]} ";
            }
            $right[] = $parts[1];
        }
    }

    shuffle($left);
    shuffle($right);

    for ($i = 0; $i < count($left); $i++) {
        $newNames[] = $left[$i] . $right[$i];
    }
    
    foreach ($newNames as $name) {
        $parts = explode('|', $name);
        $result[$parts[0]][] = $parts[1];
    }

    return $result;
}

/**
 * Функция выводит таблицу городов с группировкой по регионам.
 *
 * @param $cities
 * @param $randomCities
 *
 * @return string html разметка
 */
function renderCities($cities, $randomCities) {
    $html  = '<h1>Таблица городов (F5 для новых вариантов)</h1>';
    $html .= '<table class="table table-bordered table-condensed">';
    $html .= '<tr><th colspan="2" class="text-center active">регион</th>';
    $html .= '<tr><th class="text-center bg-info" style="width: 50%;">реальные</th>';
    $html .= '<th class="text-center bg-warning">фантазийные</th></tr>';

    foreach ( $cities as $region => $city ) {
        sort($city);
        sort($randomCities[$region]);
        $html .= '<tr class="active"><th colspan="2" class="text-center">'.$region.'</th>';
        for ($i = 0; $i < count($city); $i++) {
            $html .= '<tr><td>'.$city[$i].'</td>';
            $html .= '<td>'.$randomCities[$region][$i].'</td></tr>';
        }
    }

    $html .= '</table>';
    return $html;
}

/**
 * Функция выводит весь массив субъектов $regions.
 *
 * @param $regions
 *
 * @return string html разметка
 */
function render($regions) {
    $html = '<h1>Субъекты Российской Федерации</h1>';
    foreach ( $regions as $name => $region ) {
        $html .= "<h2>$name</h2>";
        foreach ( $region as $rname => $cities ) {
            $html .= "<h3>$rname</h3>";
            $html .= '<ul class="list-inline list-group">';
            $html .= "<li>города:</li>";
            foreach ( $cities as $city ) {
                $html .= "<li class=\"list-group-item\">$city</li>";
            }
            $html .= '</ul>';
        }
    }
    return $html;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Regions | netology</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body class="container">

    <?php
        $cities = towWordsCities($regions);
        echo renderCities($cities, randomize($cities));
        echo render($regions);
    ?>

</body>
</html>
