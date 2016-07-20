<?php

namespace App\Extensions\Loggers;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class AdminLogger {
    protected $child;
    protected $log;
    protected $params = [];
    protected $method = 'GET';

    public function __construct($client, $params = []){
        $this->child = $client;
        $this->method = $this->child->app->request->getMethod();
        $this->params = $this->child->app->request->getBody();
        if (empty($this->params['login'])) {
            $this->params['login'] = 'Guest';
        }
        $dateFormat = (!empty($params['dateFormat'])) ? $params['dateFormat'] : 'Y-m-d H:i';
        $output = (!empty($params['output'])) ? $params['output'] : "%datetime% %message%\n";
        $pathToLog = (!empty($params['pathToLog'])) ? APP_ROOT . $params['pathToLog'] : APP_ROOT . '/debug.log';
        $stream = new StreamHandler($pathToLog, Logger::DEBUG);
        $stream->setFormatter(new LineFormatter($output, $dateFormat));
        $channel = (!empty($client->adminLogin)) ? $client->adminLogin : $this->params['login'];
        $this->log = new Logger($channel);
        $this->log->pushHandler($stream);
    }

    public function __call($name,$args) {
        $msg = '';
        switch ($name) {
            case 'actionAdmin':
                if ($this->method == 'POST') {
                    $msg = 'попытка входа в систему';
                }
                break;
            case 'actionAdminLogout':
                $msg = 'вышел из системы';
                break;
            case 'actionAdminNew':
                if (!empty($this->params['new'])) {
                    $msg = "создал нового администратора {$this->params['new']['login']}";
                }
                break;
            case 'actionAdminEdit':
                if (!empty($this->params['edit'])) {
                    $msg = "изменил пароль администратора ({$this->params['edit']['who']})";
                }
                break;
            case 'actionAdminDel':
                if (!empty($this->params['del'])) {
                    $msg = "удалил администратора ({$this->params['del']['who']})";
                }
                break;
            // category logging
            case 'actionCatNew':
                if (!empty($this->params['cat'])) {
                    $msg = "создал тему \"{$this->params['cat']['name']}\"";
                }
                break;
            case 'actionCatDel':
                if (!empty($this->params['del'])) {
                    $msg = "удалил тему ({$this->params['del']['category']})";
                }
                break;
            // questions logging
            case 'actionQuestionEdit':
                if (isset($this->params['del'])) {
                    $msg = "удалил вопрос ({$this->params['q']}) из темы ({$this->params['cat']})";
                }
                if (isset($this->params['e'])) {
                    $msg = "обновил вопрос ({$this->params['q']}) из темы ({$this->params['cat']})";
                }
                break;
        }
        if ($msg) {
            $this->log->debug($msg);
        }
        return call_user_func_array(array($this->child, $name), $args);
    }
}
