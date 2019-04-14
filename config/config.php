<?php

return [
    'routes' => [
        'authorization/[?a-zA-Z=]+([0-9+])' => 'authorization/index/$1',
        'gallery_ajax' => 'site/galleryAjax',
        'rateit_save' => 'site/rateitSave',
        'exit' => 'site/exit',
        '' => 'site/index'
    ],
    'ya_config' => [
        'client_id' => 'b3fb6f2f468a4350aa06445615a7e4f6',
        'client_secret' => '3629c26863644d0ab1342cf2b69d1d9a',
        'session_name' => 'yaToken',
        'authorization' => false,
        'type' => 'image',
        'sort' => '',
        'limit' => 7,

    ],
    'db' => [
        'host' => 'localhost',
        'dbname' => 'infotechtk_gal',
        'user' => 'infotechtk_gal',
        'password' => 'xxxx1111'
    ]
];
