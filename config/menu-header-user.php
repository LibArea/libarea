<?php
/*
 * Выпадающее меню участника в шапке сайта
 * Drop-down menu of the participant in the header of the site
 */

return [
    [
        'url'   => 'user',
        'name'  => Translate::get('profile'),
        'icon'  => 'bi bi-person',
        'tl'    => 0,
    ], [
        'url'   => 'setting',
        'name'  => Translate::get('settings'),
        'icon'  => 'bi bi-gear',
        'tl'    => 0,
    ], [
        'url'   => 'user.drafts',
        'name'  => Translate::get('drafts'),
        'icon'  => 'bi bi-pencil-square',
        'tl'    => 0,
    ], [
        'url'   => 'user.notifications',
        'name'  => Translate::get('notifications'),
        'icon'  => 'bi bi-app-indicator',
        'tl'  => 0,
    ], [
        'url'   => 'user.messages',
        'name'  => Translate::get('messages'),
        'icon'  => 'bi bi-envelope',
        'tl'    => 0,
    ], [
        'url'   => 'user.favorites',
        'name'  => Translate::get('favorites'),
        'icon'  => 'bi bi-bookmark',
        'tl'    => 0,
    ], [
        'url'   => 'user.invitations',
        'name'  => Translate::get('invites'),
        'icon'  => 'bi bi-person-plus',
        'tl'    => 1,
    ], [
        'url'   => 'admin',
        'name'  => Translate::get('admin'),
        'icon'  => 'bi bi-shield-exclamation',
        'tl'    => 5,
    ], [
        'url'   => 'logout',
        'name'  => Translate::get('sign out'),
        'icon'  => 'bi bi-box-arrow-right',
        'tl'    => 0,
        'hr'    => '<hr>',
    ],
];
