<?php
/*
 * Меню админ-панели
 * Admin panel menu
 */

return [
    [
        'url'   => getUrlByName('admin'),
        'name'  => lang('admin'),
        'icon'  => 'bi bi-shield-exclamation',
        'item'  => 'admin',
    ],
    [
        'url'   => getUrlByName('admin.users'),
        'name'  => lang('users'),
        'icon'  => 'bi bi-people',
        'item'  => 'users',
    ],
    [
        'url'   => getUrlByName('admin.reports'),
        'name'  => lang('reports'),
        'icon'  => 'bi bi-flag',
        'item'  => 'reports',
    ],
    [
        'url'   => getUrlByName('admin.audits'),
        'name'  => lang('audits'),
        'icon'  => 'bi bi-exclamation-diamond',
        'item'  => 'audits',
    ],
    [
        'url'   => getUrlByName('admin.topics'),
        'name'  => lang('topics'),
        'icon'  => 'bi bi-columns-gap',
        'item'  => 'topics',
    ],
    [
        'url'   => getUrlByName('admin.invitations'),
        'name'  => lang('invites'),
        'icon'  => 'bi bi-person-plus',
        'item'  => 'invitations',
    ],
    [
        'url'   => getUrlByName('admin.posts'),
        'name'  => lang('posts'),
        'icon'  => 'bi bi-journal-text',
        'item'  => 'posts',
    ],
    [
        'url'   => getUrlByName('admin.comments'),
        'name'  => lang('comments-n'),
        'icon'  => 'bi bi-chat-dots',
        'item'  => 'comments-n',
    ],
    [
        'url'   => getUrlByName('admin.answers'),
        'name'  => lang('answers-n'),
        'icon'  => 'bi bi-chat-left-text',
        'item'  => 'answers-n',
    ],
    [
        'url'   => getUrlByName('admin.badges'),
        'name'  => lang('badges'),
        'icon'  => 'bi bi-award',
        'item'  => 'badges',
    ],
    [
        'url'   => getUrlByName('admin.webs'),
        'name'  => lang('domains'),
        'icon'  => 'bi bi-link-45deg',
        'item'  => 'domains',
    ],
    [
        'url'   => getUrlByName('admin.words'),
        'name'  => lang('stop words'),
        'icon'  => 'bi bi-badge-ad',
        'item'  => 'words',
    ],
    [
        'url'   => getUrlByName('admin.tools'),
        'name'  => lang('tools'),
        'icon'  => 'bi bi-tools',
        'item'  => 'tools',
    ],
];
