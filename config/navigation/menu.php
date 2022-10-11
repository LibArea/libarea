<?php
/*
 * Файл конфигурации меню
 * Menu configuration file
 */

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
            'url'   => url('search'),
            'title' => __('app.search'),
            'icon'  => 'search',
            'id'    => 'search',
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
            'tl'    => config('trust-levels.tl_add_draft'),
            'url'   => url('drafts'),
            'title' => __('app.drafts'),
            'id'    => '',
        ], [
            'url'   => url('notifications'),
            'title' => __('app.notifications'),
            'id'    => '',
        ], [
            'tl'    => config('trust-levels.tl_add_pm'),
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

    // Menu in the admin panel    
    // Меню в админ-панели    
    'admin' => [
        [
            'url'   => url('admin.settings.general'),
            'title' => __('admin.settings'),
            'icon'  => 'settings',
            'id'    => 'settings',
        ], [
            'url'   => url('admin.logs'),
            'title' => __('admin.logs'),
            'icon'  => 'activity',
            'id'    => 'logs',
        ], [
            'url'   => url('admin.audits'),
            'title' => __('admin.audits'),
            'icon'  => 'flag',
            'id'    => 'audits',
        ], [
            'url'   => url('admin.words'),
            'title' => __('admin.words'),
            'icon'  => 'stop',
            'id'    => 'words',
        ], [
            'url'   => url('admin.invitations'),
            'title' => __('admin.invites'),
            'icon'  => 'mail',
            'id'    => 'invitations',
        ], [
            'url'   => url('admin.badges'),
            'title' => __('admin.badges'),
            'icon'  => 'award',
            'id'    => 'badges',
        ], [
            'url'   => url('admin.logs.search'),
            'title' => __('admin.search'),
            'icon'  => 'search',
            'id'    => 'search',
        ], [
            'url'   => url('admin.css'),
            'title' => __('admin.css'),
            'icon'  => 'css',
            'id'    => 'css',
        ],
    ],
];
