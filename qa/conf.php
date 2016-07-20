<?php

return [
    'db' => [
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'qa'
    ],
    'extensions' => [
        /*'logger' => [
            'dateFormat' => 'Y-m-d H:i:s',
            'output' => "[%datetime%] %channel% %message%\n",
            'pathToLog' => '/logs/admin.log',
            'className' => 'AdminLogger'
        ]*/
    ],
    'view' => [
        'templates' => '/Templates/',
        //'cache' => '/Templates/cache'
        'cache' => false
    ],
    'clean_url' => false,
    'path_root' => '/qa',
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
        '/q/list' => 'QuestionList'
    ]
];