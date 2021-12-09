<?php
/*
 * Левое (вертикальное) меню на сайте
 * Left (vertical) menu on the website
 */

return [
    [
        'url'   => '/',
        'name'  => Translate::get('feed'),
        'icon'  => 'bi bi-sort-down',
        'item'  => 'feed',
    ], [
        'url'   => getUrlByName('topics.all'),
        'name'  => Translate::get('topics'),
        'icon'  => 'bi bi-columns-gap',
        'item'  => 'topics.all',
    ], [
        'url'   => getUrlByName('blogs.all'),
        'name'  => Translate::get('blogs'),
        'icon'  => 'bi bi-journal-text',
        'item'  => 'blogs.all',
    ], [
        'url'   => getUrlByName('users'),
        'name'  => Translate::get('users'),
        'icon'  => 'bi bi-people',
        'item'  => 'users',
    ], [
        'url'   => getUrlByName('answers'),
        'name'  => Translate::get('answers'),
        'icon'  => 'bi bi-chat-dots',
        'item'  => 'answers',
    ], [
        'url'   => getUrlByName('web'),
        'name'  => Translate::get('domains'),
        'icon'  => 'bi bi-link-45deg',
        'item'  => 'domains',
    ], 
];
