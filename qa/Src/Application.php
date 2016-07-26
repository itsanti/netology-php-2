<?php

namespace App;

class Application
{

    public $db;
    public $config = [];

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

    public function run($request, $response)
    {
        $this->config = include APP_ROOT . '/conf.php';
        $this->db = \App\Db\Connection::getConnection($this->config['db']);
        $this->session = new \App\Storages\Session();
        $this->view = new \App\Html\View($this->config['view']);
        $this->request = $request;
        $this->response = $response;
        $this->router = new Router($this->config);
        echo $this->router->route($request);
    }
}
