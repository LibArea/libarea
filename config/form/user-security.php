<?php
/*
 * Profile. Changing the password.
 * Профиль. Изменение пароля.
 */

return [
    [
      'title'   => __('old'),
      'arr'     => ['type' => 'password', 'required' => true],
      'name'    => 'password',
    ], [
      'title'   => __('new'),
      'arr'     => ['type' => 'password', 'min' => 6, 'max' => 32, 'required' => true],
      'name'    => 'password2',
    ], [
      'title'   => __('repeat'),
      'arr'     => ['type' => 'password', 'required' => true],
      'name'    => 'password3',
    ]
];
