<?php
/*
 * Menu in the catalog
 * Меню в каталоге
 */

return [
    'user' => [
        [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => getUrlByName('content.add', ['type' => 'category']),
            'title' => Translate::get('categories.s'),
            'icon'  => 'bi bi-plus-lg',
            'id'    => 'official',
        ], [
            'tl'    => 1,
            'url'   => getUrlByName('web.bookmarks'),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark',
            'id'    => 'web.bookmarks',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => getUrlByName('web.deleted'),
            'title' => Translate::get('deleted'),
            'icon'  => 'bi bi-circle',
            'id'    => 'web.deleted',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => getUrlByName('web.audits'),
            'title' => Translate::get('audits'),
            'icon'  => 'bi bi-circle',
            'id'    => 'web.audits',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => getUrlByName('admin.facets.type', ['type' => 'category']),
            'title' => Translate::get('facets'),
            'icon'  => 'bi bi-columns-gap',
            'id'    => 'official',
        ],
    ],
];
