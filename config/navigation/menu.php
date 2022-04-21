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
            'title' => __('feed'),
            'icon'  => 'bi bi-sort-down',
            'id'    => 'main',
        ], [
            'url'   => getUrlByName('topics.all'),
            'title' => __('topics'),
            'icon'  => 'bi bi-columns-gap',
            'id'    => 'topic',
        ], [
            'url'   => getUrlByName('blogs.all'),
            'title' => __('blogs'),
            'icon'  => 'bi bi-journals',
            'id'    => 'blog',
        ], [
            'url'   => getUrlByName('users.all'),
            'title' => __('users'),
            'icon'  => 'bi bi-people',
            'id'    => 'user',
        ], [
            'url'   => getUrlByName('answers'),
            'title' => __('answers'),
            'icon'  => 'bi bi-chat-dots',
            'id'    => 'answers',
        ], [
            'url'   => getUrlByName('comments'),
            'title' => __('comments'),
            'icon'  => 'bi bi-chat-quote',
            'id'    => 'comments',
        ], [
            'url'   => getUrlByName('web'),
            'title' => __('catalog'),
            'icon'  => 'bi bi-link-45deg',
            'id'    => 'catalog',
        ], [
            'hr'    => true,
        ], [
            'tl'    => UserData::USER_FIRST_LEVEL,
            'url'   => getUrlByName('favorites'),
            'title' => __('favorites'),
            'icon'  => 'bi bi-bookmark',
            'id'    => 'favorites',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => getUrlByName('admin.users'),
            'title' => __('admin'),
            'icon'  => 'bi bi-person-x',
            'id'    => 'admin',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => getUrlByName('admin.facets.all'),
            'title' => __('facets'),
            'icon'  => 'bi bi-bezier2',
            'id'    => 'admin',
        ],
    ],

    // Right drop-down menu in the site header 
    // Правое выпадающее меню в шапке сайта
    'user' => [
        [
            'url'   => '/@' . $login,
            'title' => __('profile'),
            'icon'  => 'bi bi-person',
            'id'    => '',
        ], [
            'url'   => getUrlByName('setting'),
            'title' => __('settings'),
            'icon'  => 'bi bi-gear',
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => getUrlByName('drafts'),
            'title' => __('drafts'),
            'icon'  => 'bi bi-pencil-square',
            'id'    => '',
        ], [
            'url'   => getUrlByName('notifications'),
            'title' => __('notifications'),
            'icon'  => 'bi bi-app-indicator',
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => getUrlByName('messages'),
            'title' => __('messages'),
            'icon'  => 'bi bi-envelope',
            'id'    => '',
        ], [
            'url'   => getUrlByName('favorites'),
            'title' => __('favorites'),
            'icon'  => 'bi bi-bookmark',
            'id'    => '',
        ], [
            'tl'    => 2,
            'url'   => getUrlByName('invitations'),
            'title' => __('invites'),
            'icon'  => 'bi bi-person-plus',
            'id'    => '',
        ], [
            'hr'    => 'hr',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => getUrlByName('admin'),
            'title' => __('admin'),
            'icon'  => 'bi bi-shield-exclamation',
            'id'    => '',
        ], [
            'url'   => getUrlByName('logout'),
            'title' => __('sign.out'),
            'icon'  => 'bi bi-box-arrow-right',
            'id'    => '',
        ],
    ],
];
