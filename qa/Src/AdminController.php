<?php

namespace App;

use App\Models\Category;
use \App\Models\User;

class AdminController {

    protected $app = null;
    protected $isAdmin = false;

    public function __construct($method)
    {
        $this->app = Application::getInstance();
        $this->app->session = new \App\Storages\Session();
        $this->isAdmin = $this->app->request->checkAccess();
        $this->app->view->addGlobal('admin', $this->isAdmin);
        if (!$this->isAdmin && $method !== 'actionAdmin') {
            $this->app->response->redirect($this->app->router->getPath('admin'), 303);
        }
    }

    public function actionAdmin()
    {
        $user = new User();
        $category = new Category();

        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();

            $result = $user->findCedentials($vars['login']);

            if (!empty($result)) {
                if (password_verify($vars['pass'], $result[$vars['login']])) {
                    $this->app->session->saveData('isAdmin', true);
                }
            }
            $this->app->response->redirect($this->app->router->getPath('admin'), 303);
        }

        $tplvars = [
            'tpl' => 'admin/index.phtml',
        ];

        if ($this->isAdmin) {
            $tplvars['admins'] = $user->findAll();
            $tplvars['categories'] = $category->getStat();
        }

        $this->app->response->setBody($tplvars);

        return $this->app->response->getBody($this->app->view);
    }

    public function actionAdminNew()
    {
        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $data = [
                'login' => $vars['new']['login'],
                'password' => password_hash($vars['new']['password'], PASSWORD_DEFAULT),
                'status' => $vars['new']['status'],
                'created_at' => time(),
            ];
            $user = new User();
            $user->addUser($data);
            $this->app->response->redirect($this->app->router->getPath('admin'), 303);
        }

        $this->app->response->setBody([
            'tpl' => 'admin/anew.phtml',
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionAdminEdit()
    {
        $user = new User();

        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $data = [
                'password' => password_hash($vars['edit']['password'], PASSWORD_DEFAULT)
            ];
            $user->editUserById($vars['edit']['who'], $data);
            $this->app->response->redirect($this->app->router->getPath('admin'), 303);
        }

        $this->app->response->setBody([
            'tpl' => 'admin/aedit.phtml',
            'admins' => $user->findAll()
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionAdminDel()
    {
        $user = new User();

        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $user->deleteUserById($vars['del']['who']);
            $this->app->response->redirect($this->app->router->getPath('admin'), 303);
        }

        $this->app->response->setBody([
            'tpl' => 'admin/adel.phtml',
            'admins' => $user->findAll()
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionAdminLogout()
    {
        $this->app->session->erase();
        $this->app->response->redirect($this->app->router->getPath('admin'), 303);
    }
}