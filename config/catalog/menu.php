<?php
/*
 * Menu in the catalog
 * Меню в каталоге
 */

return [
    'user' => [
        [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => url('content.add', ['type' => 'category']),
            'title' => __('web.add_category'),
            'id'    => 'official',
        ], [
            'tl'    => 1,
            'url'   => url('web.bookmarks'),
            'title' => __('web.favorites'),
            'id'    => 'web.bookmarks',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => url('web.deleted'),
            'title' => __('web.deleted'),
            'id'    => 'web.deleted',
        ], [
            'tl'    => UserData::REGISTERED_ADMIN,
            'url'   => url('admin.facets.type', ['type' => 'category']),
            'title' => __('web.facets'),
            'id'    => 'official',
        ],
    ],
];
