<?php
/*
 * Menu A of the admin panel
 * Меню а админ-панели
 */

return [
    'admin' => [
        [
            'url'   => getUrlByName('admin.logs'),
            'title' => __('logs'),
            'icon'  => 'bi-receipt',
            'id'    => 'logs',
        ], [
            'url'   => getUrlByName('admin.audits'),
            'title' => __('audits'),
            'icon'  => 'bi-exclamation-diamond',
            'id'    => 'audits',
        ], [
            'url'   => getUrlByName('admin.words'),
            'title' => __('words'),
            'icon'  => 'bi-badge-ad',
            'id'    => 'words',
        ], [
            'url'   => getUrlByName('admin.invitations'),
            'title' => __('invites'),
            'icon'  => 'bi-person-plus',
            'id'    => 'invitations',
        ], [
            'url'   => getUrlByName('admin.badges'),
            'title' => __('badges'),
            'icon'  => 'bi-award',
            'id'    => 'badges',
        ], [
            'url'   => getUrlByName('admin.search'),
            'title' => __('search'),
            'icon'  => 'bi-search',
            'id'    => 'search',
        ], [
            'url'   => getUrlByName('admin.css'),
            'title' => __('css'),
            'icon'  => 'bi-brush',
            'id'    => 'css',
        ], 
    ],
];
