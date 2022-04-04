<?php
/*
 * Authorization, registration and password recovery
 * Авторизация, регистрация и восстановление пароля
 */

return [
  'login' =>  [
    [
      'title'   => 'Email',
      'arr'     => [],
      'name'    => 'email'
    ], [
      'title'   => Translate::get('password'),
      'arr'     => ['type' => 'password', 'after_html' => '<span class="showPassword absolute gray-600 right5 mt5"><i class="bi-eye"></i></span>'],
      'name'    => 'password',
    ], [
      'title'   => Translate::get('remember.me'),
      'arr'     => ['type' => 'checkbox', 'value' => 1, 'wrap_class' => ['class' => 'rememberme']],
      'name'    => 'rememberme',
    ]
  ],

  'register' =>  [
    [
      'title'   => Translate::get('nickname'),
      'arr'     => ['required' => true, 'min' => 3, 'max' => 32, 'after_html' => '<span class="help">>= 3 ' . Translate::get('characters') . ' (' . Translate::get('english') . ')</span>'],
      'name'    => 'login'
    ], [
      'title'   => 'Email',
      'arr'     => ['required' => true, 'type' => 'email', 'after_html' => '<span class="help">' . Translate::get('work.email') . '</span>'],
      'name'    => 'email',
    ], [
      'title'   => Translate::get('password'),
      'arr'     => ['required' => true, 'type' => 'password', 'after_html' => '<span class="help">>= 8 ' . Translate::get('characters') . '</span>'],
      'name'    => 'password',
    ], [
      'title'   => Translate::get('repeat.password'),
      'arr'     => ['required' => true, 'type' => 'password'],
      'name'    => 'password_confirm',
    ],
  ],
];
