<?php

namespace App\Storages;

class Session {
    
    public static function saveData($key, $data) {
        session_start();
        $_SESSION[$key] = serialize($data);
    }

    public static function loadData($key, $once = false) {
        session_start();
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
