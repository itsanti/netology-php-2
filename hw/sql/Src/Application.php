<?php

namespace App;

abstract class Application
{
    protected static $instance;
    protected $pdo;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
            static::$instance->pdo = \App\Db\Connection::getConnection(include static::$dir.'/conf.php');
        }
        return static::$instance;
    }
    
    protected function __construct(){}
    final public function __clone(){}
    final public function __wakeup(){}
    
    public function redirect()
    {
        http_response_code(303);
        header('Location: '.strtok($_SERVER['REQUEST_URI'],'?'));
        exit;
    }

    public function xssafe($data, $encoding='UTF-8')
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
    }

}
