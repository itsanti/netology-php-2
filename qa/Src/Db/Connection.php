<?php

namespace App\Db;

use \Dibi\Connection as Dibicon;

class Connection {

    public static function getConnection($conf, $new = false)
    {
        static $instance = null;
        if (null === $instance || $new) {
            $instance = new Dibicon($conf);
            $instance->query('SET NAMES utf8;');

        }
        return $instance;
    }

    protected function __construct()
    {
    }
    protected function __clone()
    {
    }

}