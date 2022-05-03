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
            'icon'  => 'bi-receipt',
            'id'    => 'logs',
        ], [
            'url'   => url('admin.audits'),
            'title' => __('admin.audits'),
            'icon'  => 'bi-exclamation-diamond',
            'id'    => 'audits',
        ], [
            'url'   => url('admin.words'),
            'title' => __('admin.words'),
            'icon'  => 'bi-badge-ad',
            'id'    => 'words',
        ], [
            'url'   => url('admin.invitations'),
            'title' => __('admin.invites'),
            'icon'  => 'bi-person-plus',
            'id'    => 'invitations',
        ], [
            'url'   => url('admin.badges'),
            'title' => __('admin.badges'),
            'icon'  => 'bi-award',
            'id'    => 'badges',
        ], [
            'url'   => url('admin.search'),
            'title' => __('admin.search'),
            'icon'  => 'bi-search',
            'id'    => 'search',
        ], [
            'url'   => url('admin.css'),
            'title' => __('admin.css'),
            'icon'  => 'bi-brush',
            'id'    => 'css',
        ], 
    ],
];
