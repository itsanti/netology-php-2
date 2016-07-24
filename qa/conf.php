<?php

$path_root = '/qa';

return [
    'db' => [
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'qa'
    ],
    'extensions' => [
        'logger' => [
            'dateFormat' => 'Y-m-d H:i:s',
            'output' => "[%datetime%] %channel% %message%\n",
            'pathToLog' => '/logs/admin.log',
            'className' => 'AdminLogger'
        ],
        'telegram' => [
            'key' => 'secret',
            'email' => 'telegram@mysite.dev'
        ]
    ],
    'view' => [
        'templates' => '/Templates/',
        'cache' => '/Templates/cache',
        'root' => $path_root
    ],
    'clean_url' => false,
    'path_root' => $path_root,
    'routes' => [
        '' => 'Index',
        '/ask' => 'AskQuestion',
        '/admin' => 'Admin',
        '/admin/new' => 'AdminNew',
        '/admin/edit' => 'AdminEdit',
        '/admin/del' => 'AdminDel',
        '/admin/logout' => 'AdminLogout',
        '/cat/new' => 'CatNew',
        '/cat/del' => 'CatDel',
        '/cat/view' => 'CatView',
        '/q/edit' => 'QuestionEdit',
        '/q/list' => 'QuestionList',
        '/q/blist' => 'QuestionBList',
        '/q/bot' => 'QuestionBot',
        '/sw/list' => 'SWlist',
        '/sw/new' => 'SWnew',
        '/sw/del' => 'SWdel',
    ]
];