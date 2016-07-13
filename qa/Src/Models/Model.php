<?php

namespace App\Models;


abstract class Model {

    protected $app = null;

    public function __construct()
    {
        $this->app = \App\Application::getInstance();
    }

    public function findAll() {
        $result = $this->app->db->query("SELECT * FROM [{$this->tblname}]");
        return $result->fetchAll();
    }

}