<?php

namespace App\Controllers;


class BasicController {

    protected $app = null;
    protected $isAdmin = false;

    public function __construct($method)
    {
        $this->app = \App\Application::getInstance();
        $this->app->session = new \App\Storages\Session();
        $this->isAdmin = $this->app->request->checkAccess();
        $this->app->view->addGlobal('admin', $this->isAdmin);
    }

}