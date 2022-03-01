<?php
/*
 * Menu in the catalog
 * Меню в каталоге
 */

return [
    'user' => [
        [
            'tl'    => 1,
            'url'   => getUrlByName('web.bookmarks'),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark',
            'id'    => 'web.bookmarks',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => getUrlByName('category.add'),
            'title' => Translate::get('categories.s'),
            'icon'  => 'bi bi-plus-lg',
            'id'    => 'official',
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
            'url'   => getUrlByName('admin.category.structure'),
            'title' => Translate::get('structure'),
            'icon'  => 'bi bi-columns-gap',
            'id'    => 'official',
        ],
    ],
];
