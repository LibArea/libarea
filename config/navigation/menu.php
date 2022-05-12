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
            'icon'  => 'bi-sort-down',
            'id'    => 'main',
        ], [
            'url'   => url('topics.all'),
            'title' => __('app.topics'),
            'icon'  => 'bi-columns-gap',
            'id'    => 'topic',
        ], [
            'url'   => url('blogs.all'),
            'title' => __('app.blogs'),
            'icon'  => 'bi-journals',
            'id'    => 'blog',
        ], [
            'url'   => url('users.all'),
            'title' => __('app.users'),
            'icon'  => 'bi-people',
            'id'    => 'users',
        ], [
            'url'   => url('answers'),
            'title' => __('app.answers'),
            'icon'  => 'bi-chat-dots',
            'id'    => 'answers',
        ], [
            'url'   => url('comments'),
            'title' => __('app.comments'),
            'icon'  => 'bi-chat-quote',
            'id'    => 'comments',
        ], [
            'url'   => url('web'),
            'title' => __('app.catalog'),
            'icon'  => 'bi-link-45deg',
            'id'    => 'catalog',
        ], [
            'hr'    => true,
        ], [
            'tl'    => UserData::USER_FIRST_LEVEL,
            'url'   => url('favorites'),
            'title' => __('app.favorites'),
            'icon'  => 'bi-bookmark',
            'id'    => 'favorites',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => url('admin.users'),
            'title' => __('app.admin'),
            'icon'  => 'bi-person-x',
            'id'    => 'admin',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => url('admin.facets.all'),
            'title' => __('app.facets'),
            'icon'  => 'bi-bezier2',
            'id'    => 'admin',
        ],
    ],

    // Right drop-down menu in the site header 
    // Правое выпадающее меню в шапке сайта
    'user' => [
        [
            'url'   => '/@' . $login,
            'title' => __('app.profile'),
            'icon'  => 'bi-person',
            'id'    => '',
        ], [
            'url'   => url('setting'),
            'title' => __('app.settings'),
            'icon'  => 'bi-gear',
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => url('drafts'),
            'title' => __('app.drafts'),
            'icon'  => 'bi-pencil-square',
            'id'    => '',
        ], [
            'url'   => url('notifications'),
            'title' => __('app.notifications'),
            'icon'  => 'bi-app-indicator',
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => url('messages'),
            'title' => __('app.messages'),
            'icon'  => 'bi-envelope',
            'id'    => '',
        ], [
            'url'   => url('favorites'),
            'title' => __('app.favorites'),
            'icon'  => 'bi-bookmark',
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => url('invitations'),
            'title' => __('app.invites'),
            'icon'  => 'bi-person-plus',
            'id'    => '',
        ], [
            'hr'    => 'hr',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => url('admin'),
            'title' => __('app.admin'),
            'icon'  => 'bi-shield-exclamation',
            'id'    => '',
        ], [
            'url'   => url('logout'),
            'title' => __('app.sign_out'),
            'icon'  => 'bi-box-arrow-right',
            'id'    => '',
        ],
    ],
];
