<?php
/*
 * Menu A of the admin panel
 * Меню а админ-панели
 */

return [
    'admin' => [
        [
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
