<?php

namespace App\Storages;

class Session {

    private static $started = false;

    public static function saveData($key, $data) {
        if (!self::$started) {
            session_start();
            self::$started = true;
        }
        $_SESSION[$key] = serialize($data);
    }

    public static function loadData($key, $once = false) {
        if (!self::$started) {
            session_start();
            self::$started = true;
        }
        $data = null;
        if (array_key_exists($key, $_SESSION)) {
            $data = unserialize($_SESSION[$key]);
            if ($once) {
                unset($_SESSION[$key]);
            }
        }
        return $data;
    }
    
}
