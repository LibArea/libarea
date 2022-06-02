<?php
/*
 * Файл конфигурации меню
 * Menu configuration file
 */

return [

  // Navigating the Participant Settings section
  // Навигация раздела Настройки участника
  'settings' => [
    [
      'url'   => url('setting'),
      'title' => __('app.settings'),
      'icon'  => 'bi-gear mb-none',
      'id'    => 'settings',
    ], [
      'url'   => '/setting/avatar',
      'title' => __('app.avatar'),
      'icon'  => 'bi-emoji-smile mb-none',
      'id'    => 'avatar',
    ], [
      'url'   => '/setting/security',
      'title' => __('app.password'),
      'icon'  => 'bi-lock mb-none',
      'id'    => 'security',
    ], [
      'url'   => '/setting/notifications',
      'title' => __('app.notifications'),
      'icon'  => 'bi-app-indicator mb-none',
      'id'    => 'notifications',
    ],
  ],

  // Navigation section Bookmarks
  // Навигация раздела Закладки
  'favorites' => [
    [
      'id'    => 'drafts',
      'url'   => url('drafts'),
      'title' => __('app.drafts'),
      'icon'  => 'bi-journal-richtext'
    ], [
      'id'    => 'favorites',
      'url'   => url('favorites'),
      'title' => __('app.favorites'),
      'icon'  => 'bi-bookmark'
    ], [
      'id'    => 'subscribed',
      'url'   => url('subscribed'),
      'title' => __('app.subscribed'),
      'icon'  => 'bi-bookmark-plus'
    ],
  ],

  // Navigation on the central page in the feed    
  // Навигация на центральной странице в ленте
  'home' => [
    [
      'id'    => 'main.feed',
      'url'   => '/',
      'title' => __('app.feed'),
      'icon'  => 'bi-sort-down'
    ], [
      'tl'    => UserData::USER_FIRST_LEVEL,
      'id'    => 'main.all',
      'url'   => url('main.all'),
      'title' => __('app.all'),
      'icon'  => 'bi-app'
    ], [
      'id'    => 'main.top',
      'url'   => url('main.top'),
      'title' => __('app.top'),
      'icon'  => 'bi-bar-chart'
    ], [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'main.deleted',
      'url'   => url('main.deleted'),
      'title' => __('app.deleted'),
      'icon'  => 'bi-trash'
    ],
  ],

  // All answers
  // Все ответы
  'answers' => [
    [
      'tl'    => UserData::USER_ZERO_LEVEL,
      'id'    => 'answers.all',
      'url'   => '/answers',
      'title' => __('app.answers'),
      'icon'  => 'bi-sort-down'
    ],
    [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'answers.deleted',
      'url'   => url('answers.deleted'),
      'title' => __('app.deleted'),
      'icon'  => 'bi-trash'
    ],
  ],

  // All comments
  // Все комментарии
  'comments' => [
    [
      'tl'    => UserData::USER_ZERO_LEVEL,
      'id'    => 'comments.all',
      'url'   => '/comments',
      'title' => __('app.comments'),
      'icon'  => 'bi-sort-down'
    ],
    [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'comments.deleted',
      'url'   => url('comments.deleted'),
      'title' => __('app.deleted'),
      'icon'  => 'bi-trash'
    ],
  ]

];
