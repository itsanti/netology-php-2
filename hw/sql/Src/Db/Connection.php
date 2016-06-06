<?php

namespace App\Db;

class Connection {

    private static $conn;

    public static function getConnection($conf)
    {
        if (null === static::$conn) {
            $dsn = 'mysql:host='.$conf['host'].';dbname='.$conf['dbname'];
            static::$conn = new \PDO($dsn, $conf['user'], $conf['password']);
            static::$conn->exec('SET NAMES utf8;');
        }
        return static::$conn;
    }

    protected function __construct(){}

}