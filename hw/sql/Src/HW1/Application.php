<?php

namespace App\HW1;

class Application
{
    private static $instance;
    private $pdo;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
            static::$instance->pdo = \App\Db\Connection::getConnection(include __DIR__.'/conf.php');
        }
        return static::$instance;
    }
    
    protected function __construct(){}
    final public function __clone(){}
    final public function __wakeup(){}

    /**
     * Основаня логика приложения.
     */
    public function execute()
    {
        $filters = $this->getFilters();
        $content = $this->renderBooks($this->getRecords($filters));
        $content = $this->renderFilterForm($filters) . $content;
        echo $this->renderPage($content);
    }

    /**
     * Функция получает фильтры из GET параметров.
     *
     * @return array
     */
    public function getFilters()
    {
        $filters = [];

        foreach ( $_GET as $key => $value ) {
            if (in_array($key, ['isbn', 'name', 'author']) && !empty($value)) {
                $filters[$key] = $value;
            }
        }

        return $filters;
    }

    /**
     * Функция выбирает данные для таблицы.
     *
     * @param array $filters фильтры для данных
     */
    public function getRecords($filters)
    {
        $sql = 'SELECT * FROM `books`';
        $params = [];

        if (!empty($filters)) {
            $sql .= ' WHERE';
            $and = '';
            foreach ( $filters as $fname => $fvalue ) {
                $sql .= $and . " `$fname` LIKE :{$fname}";
                $params[$fname] = '%'.$fvalue.'%';
                $and = ' and';
            }
        }

        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Функция формирует таблицу книг.
     *
     * @param array $books массив данных о книгах
     * @return string html разметка
     */
    public function renderBooks($books) {
        $html  = '<table class="table table-bordered table-condensed">';
        $html .= '<tr><th class="text-center bg-success">Название</th>';
        $html .= '<th class="text-center bg-success">Автор</th>';
        $html .= '<th class="text-center bg-success">Год выпуска</th>';
        $html .= '<th class="text-center bg-success">Жанр</th>';
        $html .= '<th class="text-center bg-success">ISBN</th></tr>';

        foreach ( $books as $book ) {
            $html .= "<tr><td>{$book['name']}</td><td>{$book['author']}</td>";
            $html .= "<td class=\"text-center\" style=\"width:10%\">{$book['year']}</td><td>{$book['genre']}</td>";
            $html .= "<td class=\"text-center\" style=\"width:15%\">{$book['isbn']}</td></tr>";
        }

        $html .= '</table>';
        return $html;
    }

    /**
     * Функция формирует форму для фильтрации данных.
     *
     * @param array $filters условия фильтрации
     *
     * @return string html разметка
     */
    public function renderFilterForm($filters = [])
    {
        $tpl = file_get_contents(\TPLS . 'form.html');

        foreach ($filters as $key => $value) {
            $tpl = str_replace("{{{$key}}}", $value, $tpl);
        }

        $tpl = preg_replace("~{{.*?}}~", '', $tpl);

        return $tpl;
    }

    /**
     * Функция формирует страницу приложения.
     * 
     * @param string $content содержимое страницы
     * @return string html разметка
     */
    public function renderPage($content)
    {
        $tpl = file_get_contents(\TPLS . 'index.html');
        return str_replace("{{content}}", $content, $tpl);
    }
}
