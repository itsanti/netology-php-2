<?php

namespace App\HW4;

class ShowTableApplication extends \App\Application
{
    protected static $dir = __DIR__;
    private $twig = null;
    private $tables = null;
    private $postData = [];

    public function __construct()
    {
        $this->postData = $_GET;
        $loader = new \Twig_Loader_Filesystem(\TPLS . 'hw4');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => \TPLS . 'cache',
        ));
    }

    /**
     * Основаня логика приложения.
     */
    public function execute()
    {
        $this->tables = $this->getTables();
        $content = $this->renderForm($this->tables);
        if (isset($this->postData['table'])) {
            $data = $this->describe($this->postData['table']);
            $content .= $this->renderTables($data);
        }
        echo $this->renderPage($content);
    }

    /**
     * Функция выбирает таблицы из текущей базы.
     */
    public function getTables()
    {
        $stm = $this->pdo->prepare("SHOW TABLES");
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Функция получает структуру таблицы
     *
     * @param $table
     * @return array структура таблицы поле=>тип
     */
    public function describe($table) {
        if (!in_array($table, $this->tables)) {
            return [];
        }
        $sql = "DESCRIBE $table";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return array_column($stm->fetchAll(\PDO::FETCH_ASSOC), 'Type', 'Field');
    }

    /**
     * Функция формирует таблицу с описанием полей.
     *
     * @param array $data список полей
     *
     * @return string html разметка
     */
    public function renderTables($data)
    {
        if (empty($data)) {
            return '';
        }
        return $this->twig->render('tables.html', [
            'data' => $data
        ]);
    }
    
    /**
     * Функция формирует форму для выбора таблицы.
     *
     * @param array $data список таблиц
     *
     * @return string html разметка
     */
    public function renderForm($data)
    {
        return $this->twig->render('formTbls.html', [
            'tables' => $data,
            'current' => isset($this->postData['table']) ? $this->postData['table'] : ''
        ]);
    }

    /**
     * Функция формирует страницу приложения.
     * 
     * @param string $content содержимое страницы
     * @return string html разметка
     */
    public function renderPage($content)
    {
        return $this->twig->render('index.html', ['content' => $content]);
    }

}
