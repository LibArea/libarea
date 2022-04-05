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
      'title' => Translate::get('settings'),
      'icon'  => 'bi bi-gear',
      'id'    => 'settings',
    ], [
      'url'   => '/setting/avatar',
      'title' => Translate::get('avatar'),
      'icon'  => 'bi bi-emoji-smile',
      'id'    => 'avatar',
    ], [
      'url'   => '/setting/security',
      'title' => Translate::get('password'),
      'icon'  => 'bi bi-lock',
      'id'    => 'security',
    ], [
      'url'   => '/setting/notifications',
      'title' => Translate::get('notifications'),
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
      'title' => Translate::get('drafts'),
      'icon'  => 'bi-journal-richtext'
    ], [
      'id'    => 'favorites',
      'url'   => getUrlByName('favorites'),
      'title' => Translate::get('favorites'),
      'icon'  => 'bi-bookmark'
    ], [
      'id'    => 'subscribed',
      'url'   => getUrlByName('subscribed'),
      'title' => Translate::get('subscribed'),
      'icon'  => 'bi-bookmark-plus'
    ],
  ],

  // Navigation on the central page in the feed    
  // Навигация на центральной странице в ленте
  'home' => [
    [
      'id'    => 'main.feed',
      'url'   => '/',
      'title' => Translate::get('feed'),
      'icon'  => 'bi-sort-down'
    ], [
      'tl'    => UserData::USER_FIRST_LEVEL,
      'id'    => 'main.all',
      'url'   => getUrlByName('main.all'),
      'title' => Translate::get('all'),
      'icon'  => 'bi-app'
    ], [
      'id'    => 'main.top',
      'url'   => getUrlByName('main.top'),
      'title' => Translate::get('top'),
      'icon'  => 'bi-bar-chart'
    ], [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'main.deleted',
      'url'   => getUrlByName('main.deleted'),
      'title' => Translate::get('deleted'),
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
      'title' => Translate::get('answers'),
      'icon'  => 'bi-sort-down'
    ],
    [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'answers.deleted',
      'url'   => getUrlByName('answers.deleted'),
      'title' => Translate::get('deleted'),
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
      'title' => Translate::get('comments'),
      'icon'  => 'bi-sort-down'
    ],
    [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'comments.deleted',
      'url'   => getUrlByName('comments.deleted'),
      'title' => Translate::get('deleted'),
      'icon'  => 'bi-app'
    ],
  ]

];
