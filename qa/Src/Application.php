<?php

namespace App;

class Application
{

    protected $db;
    protected $config = [];
    
    protected function __construct()
    {
    }
    protected function __clone()
    {
    }
    /**
     * @param bool $new
     * @return static
     */
    public static function getInstance($new = false)
    {
        static $instance = null;
        if (null === $instance || $new)
            $instance = new static;
        return $instance;
    }

    protected function init()
    {
        $this->config = include APP_ROOT . '/conf.php';
        $this->db = \App\Db\Connection::getConnection($this->config['db']);
        $this->view = new \App\Html\View($this->config['view']);
    }
    
    public function run($request, $response)
    {
        $this->init();
        $result = $this->db->query('SELECT * FROM tasks');
        $response->setBody([
            'tpl' => 'index.phtml',
            'items' => $result->fetchAll(),
            'content' => 'hello, world!'
        ]);
        echo $response->getBody($this->view);
    }

    /**
     * Вспомогательный метод для перенаправления.
     * 
     * @param string $path путь для перенаправления
     * @param int $status код ответа
     */
    public function redirect($path, $status)
    {
        http_response_code($status);
        header('Location: '.$path);
        exit;
    }
}
