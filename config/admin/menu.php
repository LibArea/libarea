<?php
/*
 * Menu A of the admin panel
 * Меню а админ-панели
 */

return [
    'admin' => [
        [
            'url'   => getUrlByName('admin.users'),
            'title' => Translate::get('users'),
            'icon'  => 'bi bi-people',
            'id'    => 'users',
        ], [
            'url'   => getUrlByName('admin.logs'),
            'title' => Translate::get('logs'),
            'icon'  => 'bi bi-receipt',
            'id'    => 'logs',
        ], [
            'url'   => getUrlByName('admin.audits'),
            'title' => Translate::get('audits'),
            'icon'  => 'bi bi-exclamation-diamond',
            'id'    => 'audits',
        ], [
            'url'   => getUrlByName('admin.words'),
            'title' => Translate::get('words'),
            'icon'  => 'bi bi-badge-ad',
            'id'    => 'words',
        ], [
            'url'   => getUrlByName('admin.invitations'),
            'title' => Translate::get('invites'),
            'icon'  => 'bi bi-person-plus',
            'id'    => 'invitations',
        ], [
            'url'   => getUrlByName('admin.badges'),
            'title' => Translate::get('badges'),
            'icon'  => 'bi bi-award',
            'id'    => 'badges',
        ], [
            'url'   => getUrlByName('admin.css'),
            'title' => Translate::get('css'),
            'icon'  => 'bi bi-brush',
            'id'    => 'css',
        ],
    ],
];
