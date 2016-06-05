<?php

namespace App;

class Application
{
    private static $instance;
    
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    
    protected function __construct(){}
    final public function __clone(){}
    final public function __wakeup(){}

    public function execute()
    {
        echo 'work';
    }
}
