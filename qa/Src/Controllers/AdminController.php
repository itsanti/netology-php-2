<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Question;
use \App\Models\User;
use App\Extensions\StopWords\StopWordModel;

class AdminController extends BasicController {

    public $adminLogin;

    public function __construct($method)
    {
        parent::__construct($method);
        if (!$this->isAdmin && $method !== 'actionAdmin') {
            $this->app->response->redirect($this->app->router->getPath('admin'), 303);
        } else {
            $this->adminLogin = $this->app->session->loadData('adminLogin');
        }
    }

    public function actionAdmin()
    {
        $user = new User();
        $category = new Category();

        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();

            $result = $user->findCredentials($vars['login']);

            if (!empty($result)) {
                if (password_verify($vars['pass'], $result[$vars['login']])) {
                    $this->app->session->saveData('isAdmin', true);
                    $this->app->session->saveData('adminLogin', $vars['login']);
                }
            }
            $this->app->response->redirect($this->app->router->getPath('admin'), 303);
        }

        $tplvars = [
            'tpl' => 'admin/index.phtml',
        ];

        if ($this->isAdmin) {
            $tplvars['admins'] = $user->findAll();
            $tplvars['categories'] = $category->getQuestionStat();
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

    public function actionCatNew()
    {
        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $data = [
                'name' => $vars['cat']['name'],
                'slug' => $vars['cat']['slug']
            ];
            $category = new Category();
            $category->addCategory($data);
            $this->app->response->redirect($this->app->router->getPath('admin'), 303);
        }

        $this->app->response->setBody([
            'tpl' => 'admin/cnew.phtml',
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionCatDel()
    {
        $category = new Category();

        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $category->deleteCategoryById($vars['del']['category']);
        }

        $this->app->response->setBody([
            'tpl' => 'admin/cdel.phtml',
            'categories' => $category->findAll()
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionCatView()
    {
        $qset = [];
        $catActive = null;
        $vars = $this->app->request->getBody();

        if (!empty($vars['cat']) && is_numeric($vars['cat'])) {
            $catActive = $vars['cat'];
            $questions = new Question();
            $qset = $questions->findAllByCategory($vars['cat']);
        }

        $category = new Category();
        $this->app->response->setBody([
            'tpl' => 'admin/cview.phtml',
            'categories' => $category->findAll(),
            'questions' => $qset,
            'cat' => $catActive
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionQuestionEdit()
    {
        $qid = null;
        $cat = null;
        $question = null;
        $vars = $this->app->request->getBody();
        $words = [];

        $category = new Category();

        if (!empty($vars['q']) && is_numeric($vars['q'])) {
            $qid = $vars['q'];
            $question = new Question();
        }

        if (!empty($vars['cat']) && is_numeric($vars['cat'])) {
            $cat = $vars['cat'];
        }

        if ($this->app->request->getMethod() == 'POST') {
            if (isset($vars['del'])) {
                $question->deleteQuestion($qid);
                $this->app->response->redirect($this->app->router->getPath('cat/view', ['cat' => $cat]), 303);
            }
            if (isset($vars['e'])) {
                $data = [
                    'cat_id' => $vars['edit']['category'],
                    'status' => $vars['edit']['status'],
                    'author_name' => $vars['edit']['author_name'],
                    'author_email' => $vars['edit']['author_email'],
                    'q' => $vars['edit']['q'],
                    'a' => $vars['edit']['a']
                ];
                $question->editQuestionById($vars['q'], $data);
                $this->app->response->redirect($this->app->router->getPath('q/edit', ['q' => $vars['q'],'cat' => $vars['edit']['category']]), 303);
            }
        }

        if (!is_null($qid)) {
            $question = $question->findById($qid);
            $words = \App\Extensions\StopWords\StopWord::getWords($question);
        }

        $this->app->response->setBody([
            'tpl' => 'admin/qedit.phtml',
            'categories' => $category->findAll(),
            'question' => $question,
            'qid' => $qid,
            'cat' => $cat,
            'statuses' => [
                Question::DRAFT => 'ожидает ответа',
                Question::PUBLISHED => 'опубликован',
                Question::HIDDEN => 'скрыт',
                Question::BLOCKED => 'заблокирован'
            ],
            'words' => $words
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionQuestionList()
    {
        $questions = new Question();
        $this->app->response->setBody([
            'tpl' => 'admin/qlist.phtml',
            'questions' => $questions->findAllWithoutAnswer()
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionQuestionBList()
    {
        $questions = new Question();
        $this->app->response->setBody([
            'tpl' => 'admin/sw/swqlist.phtml',
            'questions' => $questions->findAllBlocked()
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionAdminLogout()
    {
        $this->app->session->erase();
        $this->app->response->redirect($this->app->router->getPath('admin'), 303);
    }

    public function actionSWlist()
    {
        $sw = new StopWordModel();

        $this->app->response->setBody([
            'tpl' => 'admin/sw/swview.phtml',
            'swords' => $sw->findAll(),
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionSWnew()
    {
        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $data = [
                'word' => $vars['word']
            ];
            $sw = new StopWordModel();
            $sw->addStopWord($data);
            $this->app->response->redirect($this->app->router->getPath('sw/list'), 303);
        }

        $this->app->response->setBody([
            'tpl' => 'admin/sw/swnew.phtml',
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionSWdel()
    {
        $sw = new StopWordModel();

        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $sw->deleteStopWordById($vars['del']);
        }

        $this->app->response->setBody([
            'tpl' => 'admin/sw/swdel.phtml',
            'words' => $sw->findAll(),
        ]);
        return $this->app->response->getBody($this->app->view);
    }
}