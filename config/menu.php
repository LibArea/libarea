<?php
/*
 * Файл конфигурации меню
 * Menu configuration file
 */
$account = Request::getSession('account') ?? [];
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
            'id'    => 'topics',
        ], [
            'url'   => getUrlByName('blogs.all'),
            'title' => Translate::get('blogs'),
            'icon'  => 'bi bi-journals',
            'id'    => 'blogs',
        ], [
            'url'   => getUrlByName('users.all'),
            'title' => Translate::get('users'),
            'icon'  => 'bi bi-people',
            'id'    => 'users',
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
            'url'   => getUrlByName('user.favorites', ['login' => $account['user_login'] ?? 'none']),
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

    // Left menu in the admin panel 
    // Левое меню в админ панели
    'admin' =>    [
        [
            'url'   => getUrlByName('admin'),
            'title'  => Translate::get('admin'),
            'icon'  => 'bi bi-shield-exclamation',
            'id'  => 'admin',
        ], [
            'url'   => getUrlByName('admin.users'),
            'title'  => Translate::get('users'),
            'icon'  => 'bi bi-people',
            'id'  => 'users',
        ], [
            'url'   => getUrlByName('admin.reports'),
            'title'  => Translate::get('reports'),
            'icon'  => 'bi bi-flag',
            'id'  => 'reports',
        ], [
            'url'   => getUrlByName('admin.audits'),
            'title'  => Translate::get('audits'),
            'icon'  => 'bi bi-exclamation-diamond',
            'id'  => 'audits',
        ], [
            'url'   => getUrlByName('admin.topics'),
            'title'  => Translate::get('topics'),
            'icon'  => 'bi bi-columns-gap brown',
            'id'  => 'topics',
        ], [
            'url'   => getUrlByName('admin.blogs'),
            'title'  => Translate::get('blogs'),
            'icon'  => 'bi bi-journals brown',
            'id'  => 'blogs',
        ], [
            'url'   => getUrlByName('admin.sections'),
            'title'  => Translate::get('sections'),
            'icon'  => 'bi bi-intersect brown',
            'id'  => 'sections',
        ], [            
            'url'   => getUrlByName('admin.pages'),
            'title'  => Translate::get('pages'),
            'icon'  => 'bi bi-journal-richtext brown',
            'id'  => 'pages',
        ], [
            'url'   => getUrlByName('admin.invitations'),
            'title'  => Translate::get('invites'),
            'icon'  => 'bi bi-person-plus',
            'id'  => 'invites',
        ], [
            'url'   => getUrlByName('admin.posts'),
            'title'  => Translate::get('posts'),
            'icon'  => 'bi bi-journal-text',
            'id'  => 'posts',
        ], [
            'url'   => getUrlByName('admin.comments'),
            'title'  => Translate::get('comments'),
            'icon'  => 'bi bi-chat-dots',
            'id'  => 'comments',
        ], [
            'url'   => getUrlByName('admin.answers'),
            'title'  => Translate::get('answers'),
            'icon'  => 'bi bi-chat-left-text',
            'id'  => 'answers',
        ], [
            'url'   => getUrlByName('admin.badges'),
            'title'  => Translate::get('badges'),
            'icon'  => 'bi bi-award',
            'id'  => 'badges',
        ], [
            'url'   => getUrlByName('admin.sites'),
            'title'  => Translate::get('sites'),
            'icon'  => 'bi bi-link-45deg',
            'id'  => 'sites',
        ],
    ],

    // Right drop-down menu in the site header 
    // Правое выпадающее меню в шапке сайта
    'user' => [
        [
            'url'   => getUrlByName('user', ['login' => $account['user_login'] ?? 'none']),
            'title'  => Translate::get('profile'),
            'icon'  => 'bi bi-person',
            'id'    => '',
        ], [
            'url'   => getUrlByName('setting', ['login' => $account['user_login'] ?? 'none']),
            'title'  => Translate::get('settings'),
            'icon'  => 'bi bi-gear',
            'id'    => '',
        ], [
            'url'   => getUrlByName('user.drafts', ['login' => $account['user_login'] ?? 'none']),
            'title'  => Translate::get('drafts'),
            'icon'  => 'bi bi-pencil-square',
            'id'    => '',
        ], [
            'url'   => getUrlByName('user.notifications', ['login' => $account['user_login'] ?? 'none']),
            'title'  => Translate::get('notifications'),
            'icon'  => 'bi bi-app-indicator',
            'id'  => '',
        ], [
            'url'   => getUrlByName('user.messages', ['login' => $account['user_login'] ?? 'none']),
            'title'  => Translate::get('messages'),
            'icon'  => 'bi bi-envelope',
            'id'    => '',
        ], [
            'url'   => getUrlByName('user.favorites', ['login' => $account['user_login'] ?? 'none']),
            'title'  => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark',
            'id'    => '',
        ], [
            'url'   => getUrlByName('user.invitations', ['login' => $account['user_login'] ?? 'none']),
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
