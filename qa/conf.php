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
        '/admin' => 'Admin',
        '/ask' => 'AskQuestion'
    ]
];