<?php
/*
 * Profile. Changing the password.
 * Профиль. Изменение пароля.
 */

return [
    [
      'title'   => Translate::get('old'),
      'arr'     => ['type' => 'password', 'required' => true],
      'name'    => 'password',
    ], [
      'title'   => Translate::get('new'),
      'arr'     => ['type' => 'password', 'min' => 6, 'max' => 32, 'required' => true],
      'name'    => 'password2',
    ], [
      'title'   => Translate::get('repeat'),
      'arr'     => ['type' => 'password', 'required' => true],
      'name'    => 'password3',
    ]
];
