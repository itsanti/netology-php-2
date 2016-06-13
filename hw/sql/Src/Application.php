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
