<?php
/*
 * Menu A of the admin panel
 * Меню а админ-панели
 */

return [
    'admin' => [
        [
            'url'   => getUrlByName('admin.logs'),
            'title' => Translate::get('logs'),
            'icon'  => 'bi-receipt',
            'id'    => 'logs',
        ], [
            'url'   => getUrlByName('admin.audits'),
            'title' => Translate::get('audits'),
            'icon'  => 'bi-exclamation-diamond',
            'id'    => 'audits',
        ], [
            'url'   => getUrlByName('admin.words'),
            'title' => Translate::get('words'),
            'icon'  => 'bi-badge-ad',
            'id'    => 'words',
        ], [
            'url'   => getUrlByName('admin.invitations'),
            'title' => Translate::get('invites'),
            'icon'  => 'bi-person-plus',
            'id'    => 'invitations',
        ], [
            'url'   => getUrlByName('admin.badges'),
            'title' => Translate::get('badges'),
            'icon'  => 'bi-award',
            'id'    => 'badges',
        ], [
            'url'   => getUrlByName('admin.search'),
            'title' => Translate::get('search'),
            'icon'  => 'bi-search',
            'id'    => 'search',
        ], [
            'url'   => getUrlByName('admin.css'),
            'title' => Translate::get('css'),
            'icon'  => 'bi-brush',
            'id'    => 'css',
        ], 
    ],
];
