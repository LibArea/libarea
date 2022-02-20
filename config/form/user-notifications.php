<?php
/*
 * Setting up notifications.
 * Настройка уведомлений.
 */

return [
    [
      'title'   => Translate::get('message to PM'),
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select', 'before_html' => '<h3>' . Translate::get('notification.email') . '</h3>'],
      'name'    => 'setting_email_pm',
    ], [
      'title'   => Translate::get('contacted via @'),
      'arr'     => ['options' => ['0' => Translate::get('no'), '1' => Translate::get('yes')], 'type' => 'select'],
      'name'    => 'setting_email_appealed',
    ]
];
