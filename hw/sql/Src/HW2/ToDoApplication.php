<?php

namespace App\HW2;

class ToDoApplication extends \App\Application
{
    protected static $dir = __DIR__;
    protected $home = '';
    protected $sort = 'date_added';
    private $twig = null;

    private $parameters = [
        'id' => null,
        'action' => [
            'edit',
            'done',
            'delete'
        ]
    ];

    private $sortTypes = ['date_added', 'is_done', 'description'];
    private $actualParams = [];
    private $postData = [];

    public function __construct()
    {
        $this->initApp();
    }

    public function initApp()
    {
        $this->actualParams = $this->getParams();
        $this->postData = $_POST;
        $this->home = strtok($_SERVER['REQUEST_URI'],'?');
        if (isset($this->postData['sort'])) {
            $this->setSortTasks();
        }
        $loader = new \Twig_Loader_Filesystem(\TPLS . 'hw2');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => \TPLS . 'cache',
        ));
    }

    /**
     * Основаня логика приложения.
     */
    public function execute()
    {
        if (isset($this->postData['save'])) {
            $this->saveTask();
        }
        $task = $this->doAction();
        $content = $this->renderTasks($this->getTasks());
        $content = $this->renderAddForm($task) .
                   $this->renderSortForm() . $content;
        echo $this->renderPage($content);
    }

    /**
     * Функция получает параметры из GET.
     *
     * @return array массив допустимых параметров
     */
    public function getParams()
    {
        $params = [];

        foreach ($_GET as $key => $value) {

            if (array_key_exists($key, $this->parameters) && (empty($this->parameters[$key])
                              || in_array($value, $this->parameters[$key]))) {
                $params[$key] = $value;
            }

        }

        if (!array_key_exists('action', $params)) {
            return [];
        }

        return $params;
    }

    /**
     * Функция сохраняет задачу в БД.
     */
    public function saveTask()
    {
        if (empty($this->postData['description'])) {
            return 0;
        }

        if (!empty($this->actualParams)) {
            $sql = 'UPDATE `tasks` SET `description` = ? WHERE `id` = ? LIMIT 1';
            $data = [$this->postData['description'], $this->actualParams['id']];
        } else {
            $sql = 'INSERT INTO `tasks` (`description`, `date_added`) VALUES (?, ?)';
            $data = [$this->postData['description'], date("Y-m-d H:i:s")];
        }
        $stm = $this->pdo->prepare($sql);
        $stm->execute($data);

        $this->redirect($this->home, 303);
    }

    /**
     * Функция удаляет задачу из БД
     *
     * @param int $id идентификатор задачи
     *
     * @return bool
     */
    public function delTask($id)
    {
        $sql = 'DELETE FROM `tasks` WHERE `id` = ? LIMIT 1';
        $stm = $this->pdo->prepare($sql);
        return $stm->execute([$id]);
    }

    /**
     * Функция устанавливает параметр сортировки
     */
    public function setSortTasks()
    {
        $sort = !empty($this->postData['sort_by']) ? $this->postData['sort_by'] : '';
        if (in_array($sort, $this->sortTypes)) {
            $this->sort = $sort;
        }
    }

    /**
     * Функция выбирает данные для таблицы задач.
     */
    public function getTasks()
    {
        $sql = "SELECT * FROM `tasks` ORDER BY {$this->sort}";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Функция изменяет статус задачи.
     *
     * @param int $id идентификатор задачи
     */
    public function doTask($id)
    {
        $sql = 'UPDATE `tasks` SET `is_done` = 1 WHERE `id` = ? LIMIT 1';
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id]);
        $this->redirect($this->home, 303);
    }

    /**
     * Функция возвращает редактируемую задачу.
     *
     * @param int $id идентификатор задачи
     *
     * @return bool
     */
    public function editTask($id)
    {
        $sql = 'SELECT * FROM `tasks` WHERE `id` = ?';
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id]);
        return $stm->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Функция исполняет действие над задачей.
     */
    public function doAction()
    {
        $result = false;

        if (empty($this->actualParams)) {
            return [];
        }

        switch ($this->actualParams['action']) {
            case 'edit':
                $result = $this->editTask($this->actualParams['id']);
                break;
            case 'delete':
                $result = $this->delTask($this->actualParams['id']);
                break;
            case 'done':
                $this->doTask($this->actualParams['id']);
                break;
            default:
                break;
        }

        return $result;
    }

    /**
     * Функция формирует таблицу задач.
     *
     * @param array $tasks массив задач
     * @return string html разметка
     */
    public function renderTasks($tasks) {

        return $this->twig->render('tasks.html', [
            'tasks' => $tasks
        ]);
    }

    /**
     * Функция формирует форму для добавления задачи.
     *
     * @param mixed $task задача для редактирования
     *
     * @return string html разметка
     */
    public function renderAddForm($task)
    {
        if (!is_array($task)) {
            $task = [];
        }
        return $this->twig->render('formAdd.html', [
            'description' => array_key_exists('description', $task) ? $task['description'] : '',
        ]);
    }

    /**
     * Функция формирует форму для сортировки задач.
     *
     * @return string html разметка
     */
    public function renderSortForm()
    {
        $options = [
            'date_added' => 'Дате добавления',
            'is_done' => 'Статусу',
            'description' => 'Описанию'
        ];

        return $this->twig->render('formSort.html', [
            'options' => $options,
            'sort' => $this->sort
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
