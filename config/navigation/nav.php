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
      'id'    => 'settings',
    ], [
      'url'   => '/setting/avatar',
      'title' => __('app.avatar'),
      'id'    => 'avatar',
    ], [
      'url'   => '/setting/security',
      'title' => __('app.password'),
      'id'    => 'security',
    ], [
      'url'   => '/setting/notifications',
      'title' => __('app.notifications'),
      'id'    => 'notifications',
      'css'    => 'mb-none',
    ],
  ],

  // Navigation section Bookmarks
  // Навигация раздела Закладки
  'favorites' => [
    [
      'id'    => 'favorites',
      'url'   => url('favorites'),
      'title' => __('app.favorites'),
    ], [
      'id'    => 'subscribed',
      'url'   => url('subscribed'),
      'title' => __('app.subscribed'),
    ], [
      'id'    => 'folders',
      'url'   => url('favorites.folders'),
      'title' => __('app.folders'),
    ],
  ],

  // Navigation on the central page in the feed    
  // Навигация на центральной странице в ленте
  'home' => [
    [
      'id'    => 'main.feed',
      'url'   => '/',
      'title' => __('app.feed'),
    ], [
      'tl'    => UserData::USER_FIRST_LEVEL,
      'id'    => 'main.all',
      'url'   => url('main.all'),
      'title' => __('app.all'),
    ], [
      'id'    => 'main.top',
      'url'   => url('main.top'),
      'title' => __('app.top'),
    ], [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'main.deleted',
      'url'   => url('main.deleted'),
      'title' => __('app.deleted'),
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
    ],
    [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'answers.deleted',
      'url'   => url('answers.deleted'),
      'title' => __('app.deleted'),
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
    ],
    [
      'tl'    => UserData::REGISTERED_ADMIN,
      'id'    => 'comments.deleted',
      'url'   => url('comments.deleted'),
      'title' => __('app.deleted'),
    ],
  ]

];
