<?php

namespace App\HW2;

class Application extends \App\Application
{
    protected static $dir = __DIR__;
    protected $redirect = false;
    protected $sort = 'date_added';

    protected $actions = [
        'edit' => ['Изменить', 'btn-info'],
        'done' => ['Выполнить', 'btn-success'],
        'delete' => ['Удалить', 'btn-danger']
    ];

    /**
     * Основаня логика приложения.
     */
    public function execute()
    {
        if (isset($_POST['save'])) {
            $this->saveTask();
            $this->redirect();
        }
        if (isset($_POST['sort'])) {
            $this->setSortTasks();
        }
        if (isset($_POST['del'])) {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            $this->delTask($id);
            $this->redirect();
        }
        $task = $this->doTask();
        if ($this->redirect) {
            $this->redirect();
        }
        if (array_key_exists('delete', $task)) {
            unset($task['delete']);
            $content = $this->renderDelForm($task);
        } else {
            $content = $this->renderTasks($this->getTasks());
            $content = $this->renderAddForm($task) .
                       $this->renderSortForm() . $content;
        }
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

        foreach ( $_GET as $key => $value ) {
            if (in_array($key, ['id', 'action']) && !empty($value)) {
                $params[$key] = $value;
            }
        }

        if (count($params) == 2) {
            if (!in_array($params['action'], array_keys($this->actions))) {
                return [];
            }
            $params['id'] = (int) $params['id'];
        }

        return $params;
    }

    /**
     * Функция сохраняет задачу в БД.
     *
     * @return int ID задачи в БД
     */
    public function saveTask()
    {
        if (empty($_POST['description'])) {
            return 0;
        }

        $params = $this->getParams();

        if (!empty($params)) {
            $sql = 'UPDATE `tasks` SET `description` = ? WHERE `id` = ? LIMIT 1';
            $data = [$_POST['description'], $params['id']];
        } else {
            $data = [$_POST['description'], date("Y-m-d H:i:s")];
            $sql = 'INSERT INTO `tasks` (`description`, `date_added`) VALUES (?, ?)';
        }
        $stm = $this->pdo->prepare($sql);
        $stm->execute($data);
        return $this->pdo->lastInsertId();
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
        if (is_null($id)) {
            return false;
        }
        $sql = 'DELETE FROM `tasks` WHERE `id` = ? LIMIT 1';
        $stm = $this->pdo->prepare($sql);
        return $stm->execute([$id]);
    }

    /**
     * Функция устанавливает параметр сортировки
     */
    public function setSortTasks()
    {
        $sort = !empty($_POST['sort_by']) ? $_POST['sort_by'] : '';
        if (in_array($sort, ['date_added', 'is_done', 'description'])) {
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
     * Функция исполняет действие над задачей.
     */
    public function doTask()
    {
        $params = $this->getParams();
        $task = [];

        if (empty($params)) {
            return $task;
        }

        switch ($params['action']) {
            case 'edit':
            case 'delete':
                $sql = 'SELECT * FROM `tasks` WHERE `id` = ?';
                break;
            case 'done':
                $sql = 'UPDATE `tasks` SET `is_done` = 1 WHERE `id` = ? LIMIT 1';
                $this->redirect = true;
                break;
        }

        $stm = $this->pdo->prepare($sql);
        $stm->execute([$params['id']]);

        if ($params['action'] == 'delete') {
            $task['delete'] = true;
        }

        $result = $stm->fetch(\PDO::FETCH_ASSOC);

        return $task + (!empty($result) ? $result : []);
    }

    /**
     * Функция формирует таблицу задач.
     *
     * @param array $tasks массив задач
     * @return string html разметка
     */
    public function renderTasks($tasks) {

        $taskStatus = [
            '<span class="text-warning">В процессе</span>',
            '<span class="text-success">Выполнено</span>'
        ];

        $html  = '<table class="table table-bordered table-condensed">';
        $html .= '<tr><th class="text-center bg-success">Описание задачи</th>';
        $html .= '<th class="text-center bg-success">Дата добавления</th>';
        $html .= '<th class="text-center bg-success">Статус</th>';
        $html .= '<th class="text-center bg-success">Управление</th></tr>';

        foreach ( $tasks as $task ) {
            $task['description'] = $this->xssafe($task['description']);
            $html .= "<tr><td style=\"width:50%\">{$task['description']}</td>" .
                     "<td class=\"text-center\" style=\"width:15%\">{$task['date_added']}</td>";
            $html .= "<td class=\"text-center\" style=\"width:10%\">{$taskStatus[$task['is_done']]}</td>";
            $html .= "<td>{$this->renderAdminMenu($task['id'])}</td></tr>";
        }

        $html .= '</table>';
        return $html;
    }

    /**
     * Функция формирует разметку для действий над записями.
     *
     * @param int $id идентификатор записи
     *
     * @return string html разметка
     */
    public function renderAdminMenu($id)
    {
        $html = '<ul class="list-inline">';
        foreach ($this->actions as $action => $prop) {
            $html .= "<li><a href=\"?id={$id}&action={$action}\"" .
                     " class=\"btn {$prop[1]} btn-xs\">{$prop[0]}</a></li>";
        }
        return $html;
    }

    /**
     * Функция формирует форму для добавления задачи.
     *
     * @param array $task задача для редактирования
     *
     * @return string html разметка
     */
    public function renderAddForm($task = [])
    {
        $subValue = 'Добавить';

        if (!empty($task)) {
            $task = $this->xssafe($task['description']);
            $subValue = 'Сохранить';
        } else {
            $task = '';
        }

        $tpl = file_get_contents(\TPLS . 'hw2/formAdd.html');
        $tpl = str_replace(
            ["{{description}}", '{{subValue}}'],
            [$task, $subValue], $tpl
        );
        return $tpl;
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

        $html = '';
        foreach ( $options as $opt => $val ) {
            if ($opt == $this->sort) {
                $html .= "<option value=\"$opt\" selected>{$val}</option>";
            } else {
                $html .= "<option value=\"$opt\">{$val}</option>";
            }
        }
        $tpl = file_get_contents(\TPLS . 'hw2/formSort.html');
        $tpl = str_replace("{{options}}", $html, $tpl);
        return $tpl;
    }

    /**
     * Функция формирует форму для удаления задачи.
     *
     * @param array $task задача для удаления
     *
     * @return string html разметка
     */
    public function renderDelForm($task = [])
    {
        if (empty($task)) {
            $this->redirect();
        }
        $tpl = file_get_contents(\TPLS . 'hw2/formDel.html');
        $tpl = str_replace(
            ["{{description}}", '{{id}}'],
            [$this->xssafe($task['description']), $task['id']], $tpl
        );
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
        $tpl = file_get_contents(\TPLS . 'hw2/index.html');
        return str_replace("{{content}}", $content, $tpl);
    }
}
