<?php
/*
 * Authorization, registration and password recovery
 * Авторизация, регистрация и восстановление пароля
 */

return [
  'login' =>  [
    [
    //  'title'   => 'Email',
      'arr'     => ['placeholder' => 'Email'],
      'name'    => 'email'
    ], [
    //  'title'   => __('app.password'),
      'arr'     => ['placeholder' => __('app.password'), 'type' => 'password', 'after_html' => '<span class="showPassword absolute gray-600 right5 mt5"><i class="bi-eye"></i></span>'],
      'name'    => 'password',
    ], [
      'title'   => __('app.remember_me'),
      'arr'     => ['type' => 'checkbox', 'value' => 1, 'wrap_class' => ['class' => 'rememberme']],
      'name'    => 'rememberme',
    ]
  ],

  'register' =>  [
    [
      'title'   => __('app.nickname'),
      'arr'     => ['required' => true, 'min' => 3, 'max' => 32, 'after_html' => '<span class="help">>= 3 ' . __('app.characters') . ' (' . __('app.english') . ')</span>'],
      'name'    => 'login'
    ], [
      'title'   => 'Email',
      'arr'     => ['required' => true, 'type' => 'email', 'after_html' => '<span class="help">' . __('app.work_email') . '</span>'],
      'name'    => 'email',
    ], [
      'title'   => __('app.password'),
      'arr'     => ['required' => true, 'type' => 'password', 'after_html' => '<span class="help">>= 8 ' . __('app.characters') . '</span>'],
      'name'    => 'password',
    ], [
      'title'   => __('app.repeat_password'),
      'arr'     => ['required' => true, 'type' => 'password'],
      'name'    => 'password_confirm',
    ],
  ],
];
