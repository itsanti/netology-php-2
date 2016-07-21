<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Question;

class Controller extends BasicController {

    public function actionIndex()
    {
        $cats = new Category();
        $questions = new Question();
        $cats = $cats->findAll();
        foreach ($cats as $cat) {
            $cat->questions = $questions->findByCategory($cat->id);
        }
        $this->app->response->setBody([
            'tpl' => 'index.phtml',
            'cats' => $cats
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function actionAskQuestion()
    {
        if ($this->app->request->getMethod() == 'POST') {
            $vars = $this->app->request->getBody();
            $data = [
                'cat_id' => $vars['ask']['category'],
                'postdate' => time(),
                'author_name' => $vars['ask']['name'],
                'author_email' => $vars['ask']['email'],
                'q' => $vars['ask']['question']
            ];

            if (!\App\Extensions\StopWords\StopWord::isClean($data['q'])) {
                $data['status'] = Question::BLOCKED;
            }

            $question = new Question();
            $question->addQuestion($data);
            $this->app->response->redirect($this->app->router->getPath('Index'), 303);
        }
        $cats = new Category();
        $this->app->response->setBody([
            'tpl' => 'ask.phtml',
            'cats' => $cats->findAll()
        ]);
        return $this->app->response->getBody($this->app->view);
    }

    public function action404()
    {
        return '404';
    }
}