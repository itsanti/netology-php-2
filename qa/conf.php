<?php

return [
    'db' => [
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'qa'
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
        '/admin/logout' => 'AdminLogout'
    ]
];