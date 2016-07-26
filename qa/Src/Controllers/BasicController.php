<?php

namespace App\Controllers;


abstract class BasicController {

    public $app = null;
    protected $isAdmin = false;

    public function __construct($method)
    {
        $this->app = \App\Application::getInstance();
        $this->isAdmin = $this->app->request->checkAccess();
        $this->app->view->addGlobal('admin', $this->isAdmin);
    }

}