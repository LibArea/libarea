<?php
/*
 * Файл конфигурации меню
 * Menu configuration file
 */
$account = \UserData::get();
$login = $account['login'] ?? null;
return [

    // Left general menu
    // Левое общее меню
    'left' => [
        [
            'url'   => '/',
            'title' => __('app.feed'),
            'icon'  => 'monitor',
            'id'    => 'main',
        ], [
            'url'   => url('topics.all'),
            'title' => __('app.topics'),
            'icon'  => 'hash',
            'id'    => 'topic',
        ], [
            'url'   => url('blogs.all'),
            'title' => __('app.blogs'),
            'icon'  => 'book',
            'id'    => 'blog',
        ], [
            'url'   => url('users.all'),
            'title' => __('app.users'),
            'icon'  => 'users',
            'id'    => 'users',
        ], [
            'url'   => url('answers'),
            'title' => __('app.answers'),
            'icon'  => 'answers',
            'id'    => 'answers',
        ], [
            'url'   => url('comments'),
            'title' => __('app.comments'),
            'icon'  => 'comments',
            'id'    => 'comments',
        ], [
            'url'   => url('web'),
            'title' => __('app.catalog'),
            'icon'  => 'link',
            'id'    => 'catalog',
        ], [
            'hr'    => true,
        ], [
            'tl'    => UserData::USER_FIRST_LEVEL,
            'url'   => url('favorites'),
            'title' => __('app.favorites'),
            'icon'  => 'bookmark',
            'id'    => 'favorites',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => url('admin.users'),
            'title' => __('app.admin'),
            'icon'  => 'users',
            'id'    => 'admin',
        ],
    ],

    // Right drop-down menu in the site header 
    // Правое выпадающее меню в шапке сайта
    'user' => [
        [
            'url'   => url('setting'),
            'title' => __('app.settings'),
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => url('drafts'),
            'title' => __('app.drafts'),
            'id'    => '',
        ], [
            'url'   => url('notifications'),
            'title' => __('app.notifications'),
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => url('messages'),
            'title' => __('app.messages'),
            'id'    => '',
        ], [
            'url'   => url('favorites'),
            'title' => __('app.favorites'),
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => url('invitations'),
            'title' => __('app.invites'),
            'id'    => '',
        ], [
            'hr'    => 'hr',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => url('admin'),
            'title' => __('app.admin'),
            'id'    => '',
        ], [
            'url'   => url('logout'),
            'title' => __('app.sign_out'),
            'id'    => '',
        ],
    ],
];
