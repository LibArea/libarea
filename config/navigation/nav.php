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
      'url'   => getUrlByName('setting'),
      'title' => __('settings'),
      'icon'  => 'bi bi-gear',
      'id'    => 'settings',
    ], [
      'url'   => '/setting/avatar',
      'title' => __('avatar'),
      'icon'  => 'bi bi-emoji-smile',
      'id'    => 'avatar',
    ], [
      'url'   => '/setting/security',
      'title' => __('password'),
      'icon'  => 'bi bi-lock',
      'id'    => 'security',
    ], [
      'url'   => '/setting/notifications',
      'title' => __('notifications'),
      'icon'  => 'bi bi-app-indicator',
      'id'    => 'notifications',
    ],
  ],

  // Navigation section Bookmarks
  // Навигация раздела Закладки
  'favorites' => [
    [
      'id'    => 'drafts',
      'url'   => getUrlByName('drafts'),
      'title' => __('drafts'),
      'icon'  => 'bi-journal-richtext'
    ], [
      'id'    => 'favorites',
      'url'   => getUrlByName('favorites'),
      'title' => __('favorites'),
      'icon'  => 'bi-bookmark'
    ], [
      'id'    => 'subscribed',
      'url'   => getUrlByName('subscribed'),
      'title' => __('subscribed'),
      'icon'  => 'bi-bookmark-plus'
    ],
  ],

  // Navigation on the central page in the feed    
  // Навигация на центральной странице в ленте
  'home' => [
    [
      'id'    => 'main.feed',
      'url'   => '/',
      'title' => __('feed'),
      'icon'  => 'bi-sort-down'
    ], [
      'tl'    => UserData::USER_FIRST_LEVEL,
      'id'    => 'main.all',
      'url'   => getUrlByName('main.all'),
      'title' => __('all'),
      'icon'  => 'bi-app'
    ], [
      'id'    => 'main.top',
      'url'   => getUrlByName('main.top'),
      'title' => __('top'),
      'icon'  => 'bi-bar-chart'
    ], [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'main.deleted',
      'url'   => getUrlByName('main.deleted'),
      'title' => __('deleted'),
      'icon'  => 'bi-bar-chart'
    ],
  ],

  // All answers
  // Все ответы
  'answers' => [
    [
      'tl'    => UserData::USER_ZERO_LEVEL,
      'id'    => 'answers.all',
      'url'   => '/answers',
      'title' => __('answers'),
      'icon'  => 'bi-sort-down'
    ],
    [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'answers.deleted',
      'url'   => getUrlByName('answers.deleted'),
      'title' => __('deleted'),
      'icon'  => 'bi-app'
    ],
  ],

  // All comments
  // Все комментарии
  'comments' => [
    [
      'tl'    => UserData::USER_ZERO_LEVEL,
      'id'    => 'comments.all',
      'url'   => '/comments',
      'title' => __('comments'),
      'icon'  => 'bi-sort-down'
    ],
    [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'comments.deleted',
      'url'   => getUrlByName('comments.deleted'),
      'title' => __('deleted'),
      'icon'  => 'bi-app'
    ],
  ]

];
