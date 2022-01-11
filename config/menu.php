<?php
/*
 * Файл конфигурации меню
 * Menu configuration file
 */
$account = \UserData::getUid();
return [

    // Left general menu
    // Левое общее меню
    'left' => [
        [
            'url'   => '/',
            'title' => Translate::get('feed'),
            'icon'  => 'bi bi-sort-down',
            'id'    => 'main',
        ], [
            'url'   => getUrlByName('topics.all'),
            'title' => Translate::get('topics'),
            'icon'  => 'bi bi-columns-gap',
            'id'    => 'topic',
        ], [
            'url'   => getUrlByName('blogs.all'),
            'title' => Translate::get('blogs'),
            'icon'  => 'bi bi-journals',
            'id'    => 'blog',
        ], [
            'url'   => getUrlByName('users.all'),
            'title' => Translate::get('users'),
            'icon'  => 'bi bi-people',
            'id'    => 'user',
        ], [
            'url'   => getUrlByName('answers'),
            'title' => Translate::get('answers'),
            'icon'  => 'bi bi-chat-dots',
            'id'    => 'answers',
        ], [
            'url'   => getUrlByName('comments'),
            'title' => Translate::get('comments'),
            'icon'  => 'bi bi-chat-quote',
            'id'    => 'comments',
        ], [
            'url'   => getUrlByName('web'),
            'title' => Translate::get('domains'),
            'icon'  => 'bi bi-link-45deg',
            'id'    => 'domains',
        ], [
            'hr'    => true,
        ], [
            'auth'  => true,
            'tl'    => 0,
            'url'   => getUrlByName('favorites'),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark',
            'id'    => 'favorites',
        ], [
            'auth'  => true,
            'tl'    => 4,
            'url'   => getUrlByName('admin.users'),
            'title' => Translate::get('admin'),
            'icon'  => 'bi bi-person-x',
            'id'    => 'admin',
        ],
    ],

    // Right drop-down menu in the site header 
    // Правое выпадающее меню в шапке сайта
    'user' => [
        [
            'url'   => getUrlByName('profile', ['login' => $account['user_login'] ?? 'none']),
            'title'  => Translate::get('profile'),
            'icon'  => 'bi bi-person',
            'id'    => '',
        ], [
            'url'   => getUrlByName('setting'),
            'title'  => Translate::get('settings'),
            'icon'  => 'bi bi-gear',
            'id'    => '',
        ], [
            'url'   => getUrlByName('drafts'),
            'title'  => Translate::get('drafts'),
            'icon'  => 'bi bi-pencil-square',
            'id'    => '',
        ], [
            'url'   => getUrlByName('notifications'),
            'title'  => Translate::get('notifications'),
            'icon'  => 'bi bi-app-indicator',
            'id'  => '',
        ], [
            'url'   => getUrlByName('messages'),
            'title'  => Translate::get('messages'),
            'icon'  => 'bi bi-envelope',
            'id'    => '',
        ], [
            'url'   => getUrlByName('favorites'),
            'title'  => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark',
            'id'    => '',
        ], [
            'url'   => getUrlByName('invitations'),
            'title'  => Translate::get('invites'),
            'icon'  => 'bi bi-person-plus',
            'id'    => '',
        ], [
            'hr'   => 'hr',
        ], [
            'auth'  => true,
            'tl'    => 4,
            'url'   => getUrlByName('admin'),
            'title'  => Translate::get('admin'),
            'icon'  => 'bi bi-shield-exclamation',
            'id'    => '',
        ], [
            'url'   => getUrlByName('logout'),
            'title'  => Translate::get('sign out'),
            'icon'  => 'bi bi-box-arrow-right',
            'id'    => '',
        ],
    ],

    // Left dropdown menu for mobile devices in the site header
    // Левое выпадающее меню для мобильных устройств в шапке сайта
    'mobile' => [
        [
            'url'   => getUrlByName('topics.all'),
            'title' => Translate::get('topics'),
            'icon'  => 'bi bi-columns-gap',
            'id'    => '',
        ], [
            'url'   => getUrlByName('blogs.all'),
            'title' => Translate::get('blogs'),
            'icon'  => 'bi bi-journals',
            'id'    => '',
        ], [
            'url'   => getUrlByName('users.all'),
            'title' => Translate::get('users'),
            'icon'  => 'bi bi-people',
            'id'    => '',
        ], [
            'url'   => getUrlByName('web'),
            'title'  => Translate::get('domains'),
            'icon'  => 'bi bi-link-45deg',
            'id'    => '',
        ], [
            'url'   => getUrlByName('search'),
            'title'  => Translate::get('search'),
            'icon'  => 'bi bi-search',
            'id'    => '',
        ],
    ],

];
