<?php

namespace App\Storages;

class Session {

    protected $started = false;

    public function __construct()
    {
        session_start();
        $this->started = true;
    }

    public function saveData($key, $data) {
        $_SESSION[$key] = $data;
    }

    public function loadData($key, $once = false) {
        $data = null;
        if (array_key_exists($key, $_SESSION)) {
            $data = $_SESSION[$key];
            if ($once) {
                unset($_SESSION[$key]);
            }
        }
        return $data;
    }

    public function erase()
    {
        session_unset();
        session_destroy();
    }
    
}
