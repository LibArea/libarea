<?php
/*
 * Выпадающее меню участника в шапке сайта (телефон)
 * Drop-down menu of the participant in the header of the site (mobile phone) 
 */

return [
    [
        'url'   => 'topics.all',
        'name'  => Translate::get('topics'),
        'icon'  => 'bi bi-columns-gap',
    ], [
        'url'   => 'blogs.all',
        'name'  => Translate::get('blogs'),
        'icon'  => 'bi bi-journal-text',
    ], [
        'url'   => 'users',
        'name'  => Translate::get('users'),
        'icon'  => 'bi bi-people',
    ], [
        'url'   => 'web',
        'name'  => Translate::get('domains'),
        'icon'  => 'bi bi-link-45deg',
    ], [
        'url'   => 'search',
        'name'  => Translate::get('search'),
        'icon'  => 'bi bi-search',
    ],
];
