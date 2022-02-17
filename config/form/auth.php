<?php
/*
 * Authorization, registration and password recovery
 * Авторизация, регистрация и восстановление пароля
 */

return [
  'login' =>  [
    [
      'title'   => 'Email',
      'tl'      => 0, // member's trust level 
      'arr'     => [],
      'name'    => 'email'
    ], [
      'title'   => Translate::get('password'),
      'tl'      => 0,
      'arr'     => ['type' => 'password', 'after_html' => '<span class="showPassword absolute gray-400 right5 mt5"><i class="bi bi-eye"></i></span>'],
      'name'    => 'password',
    ], [
      'title'   => Translate::get('remember me'),
      'tl'      => 0,
      'arr'     => ['type' => 'checkbox', 'value' => 1, 'wrap_class' => ['class' => 'rememberme']],
      'name'    => 'rememberme',
    ]
  ],

  'register' =>  [
    [
      'title'   => Translate::get('nickname'),
      'tl'      => 0,
      'arr'     => ['required' => true, 'min' => 3, 'max' => 32, 'after_html' => '<span class="help">>= 3 ' . Translate::get('characters') . ' (' . Translate::get('english') . ')</span>'],
      'name'    => 'login'
    ], [
      'title'   => 'Email',
      'tl'      => 0,
      'arr'     => ['required' => true, 'type' => 'email', 'after_html' => '<span class="help">' . Translate::get('work email') . '</span>'],
      'name'    => 'email',
    ], [
      'title'   => Translate::get('password'),
      'tl'      => 0,
      'arr'     => ['required' => true, 'type' => 'password', 'after_html' => '<span class="help">>= 8 ' . Translate::get('characters') . '</span>'],
      'name'    => 'password',
    ], [
      'title'   => Translate::get('repeat the password'),
      'tl'      => 0,
      'arr'     => ['required' => true, 'type' => 'password'],
      'name'    => 'password_confirm',
    ],
  ],
];
