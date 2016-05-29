<?php
$wrapper = [
    'to' => 'a',
    'b' => '<div class="form-group">',
    'e' => '</div>'
];
$params = [
    'action' => '{{action}}',
    'method' => 'post',
    'enctype' => 'application/x-www-form-urlencoded',
    'wrapper' => [
        'b' =>'<div class="row"><div class="col-xs-3">',
        'e' => '</div></div>'
    ]
];
$fields = [
    [
        'element' => 'input',
        'type' => 'text',
        'opt' => [
            'id' => 'n',
            'text' => 'Имя',
            'name' => 'user_name',
            'tplvar' => '{{name}}',
            'wrapper' => $wrapper,
            'validate' => [
                'pattern' => '~.{3,}~',
                'text' => 'имя должно быть не меньше 3-х символов'
            ]
        ],
    ],
    [
        'element' => 'input',
        'type' => 'text',
        'opt' => [
            'id' => 'f',
            'text' => 'Фамилия',
            'name' => 'user_lname',
            'tplvar' => '{{lname}}',
            'wrapper' => $wrapper,
            'validate' => [
                'pattern' => '~.{3,}~',
                'text' => 'фамилия должна быть не меньше 3-х символов'
            ]
        ],
    ],
    [
        'element' => 'input',
        'type' => 'date',
        'opt' => [
            'id' => 'b',
            'text' => 'Дата рождения',
            'name' => 'user_bday',
            'tplvar' => '{{bday}}',
            'wrapper' => $wrapper
        ],
    ],
    [
        'element' => 'input',
        'type' => 'url',
        'opt' => [
            'id' => 'u',
            'text' => 'Вебсайт',
            'name' => 'user_url',
            'tplvar' => '{{url}}',
            'wrapper' => $wrapper
        ],
    ],
    [
        'element' => 'button',
        'type' => 'submit',
        'opt' => [
            'class' => 'btn-success',
            'id' => 'b-submit',
            'text' => 'отправить',
            'wrapper' => [
                'to' => 'b',
                'html' => '<div class="form-group">',
            ]
        ],
    ],
    [
        'element' => 'button',
        'type' => 'button',
        'opt' => [
            'class' => 'btn-info',
            'id' => 'ajax',
            'text' => 'отправить Ajax',
            'wrapper' => [
                'to' => 'e',
                'html' => '</div>',
            ]
        ],
    ],
];

return [
    $params, $fields
];
