<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\User;
use App\Extensions\StopWords\StopWordModel;
use App\Extensions\Telegram\TelegramBot;
use App\Extensions\Telegram\TelegramModel;

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
        $errors = [];

        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $data = [
                'login' => $vars['new']['login'],
                'password' => password_hash($vars['new']['password'], PASSWORD_DEFAULT),
                'status' => $vars['new']['status'],
                'created_at' => time(),
            ];
            $user = new User();
            if (!$user->checkLogin($data['login'])) {
                $user->addUser($data);
                $this->app->response->redirect($this->app->router->getPath('admin'), 303);
            } else {
                $errors[] = 'Такой Администратор уже существует.';
            }
        }

        $this->app->response->setBody([
            'tpl' => 'admin/anew.phtml',
            'errors' => $errors
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
            if ($vars['edit']['status'] >= 0) {
                $data['status'] = $vars['edit']['status'];
            }
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
        $bot_id = null;
        $cat = null;
        $words = [];
        $questionData = [];
        $vars = $this->app->request->getBody();

        $category = new Category();

        if (!empty($vars['q']) && is_numeric($vars['q'])) {
            $qid = $vars['q'];
            $question = new Question();
            $questionData = $question->findById($qid);
            if (!is_null($questionData)) {
                $bot_id = $questionData['bot_id'];
                $words = \App\Extensions\StopWords\StopWord::getWords($questionData['q']);
            } else {
                $this->app->response->redirect($this->app->router->getPath('cat/view', ['cat' => $cat]), 303);
            }
        }

        if (!empty($vars['cat']) && is_numeric($vars['cat'])) {
            $cat = $vars['cat'];
        }

        if ($this->app->request->getMethod() == 'POST' && !empty($qid)) {
            if (isset($vars['del'])) {
                $question->deleteQuestion($qid);
                if ($bot_id) {
                    $telModel = new TelegramModel();
                    $telModel->deleteQuestion($bot_id);
                    $this->app->response->redirect($this->app->router->getPath('q/bot'), 303);
                }
                $this->app->response->redirect($this->app->router->getPath('cat/view', ['cat' => $cat]), 303);
            }
            if (isset($vars['e'])) {

                $redirect = ['q' => $vars['q']];
                $hasCategory = !empty($vars['edit']['category']);

                if ($hasCategory) {
                    $redirect['cat'] = $vars['edit']['category'];
                }

                if (!empty($vars['edit']['a']) && $bot_id) {
                    $telegram = new TelegramBot($this->app->config['extensions']['telegram']);
                    $telModel = new TelegramModel();
                    $params = $telModel->findById($bot_id);
                    try {
                        if (!is_null($params)) {
                            $params['text'] = $vars['edit']['a'];
                        } else {
                            throw new \Telegram\Bot\Exceptions\TelegramSDKException('Record with $bot_id = ' . $bot_id . 'not found');
                        }
                        $telegram->sendAnswer($params);
                    } catch (\Exception $e) {
                        $vars['edit']['status'] = Question::DRAFT;
                        $vars['edit']['a'] = "[сбой отпарвки]\n" . $vars['edit']['a'];
                    }
                }

                $data = [
                    'cat_id' => $hasCategory ? $vars['edit']['category'] : NULL,
                    'author_name' => $vars['edit']['author_name'],
                    'author_email' => $vars['edit']['author_email'],
                    'q' => $vars['edit']['q'],
                    'a' => $vars['edit']['a'],
                    'status' => $vars['edit']['status']
                ];

                $question->editQuestionById($vars['q'], $data);

                $this->app->response->redirect($this->app->router->getPath('q/edit', $redirect), 303);
            }
        }

        $this->app->response->setBody([
            'tpl' => 'admin/qedit.phtml',
            'categories' => $category->findAll(),
            'question' => $questionData,
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

    public function actionQuestionBot()
    {
        $questions = new Question();

        $telegram = new TelegramBot($this->app->config['extensions']['telegram']);

        if (!empty($telegram->getUpdates())) {
            try {
                $qFromTelegram = $telegram->processUpdates();
            } catch (\Exception $e) {
                $qFromTelegram = [];
            }


            if (!empty($qFromTelegram)) {
                foreach ($qFromTelegram as $question) {
                    if (empty($question['q'])) {
                        continue;
                    }
                    $telModel = new TelegramModel();
                    $bot_id = $telModel->addQuestion([
                        'chat_id' => $question['chat_id'],
                        'msg_id'  => $question['msg_id']
                    ]);
                    unset($question['chat_id'], $question['msg_id']);
                    $question['bot_id'] = $bot_id;
                    $questions->addQuestion($question);
                }
            }
        }

        $this->app->response->setBody([
            'tpl' => 'admin/botqlist.phtml',
            'questions' => $questions->findFromBot()
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
