<?php
/*
 * Setting up notifications.
 * Настройка уведомлений.
 */

return [
    [
      'title'   => __('message.PM'),
      'arr'     => ['options' => ['0' => __('no'), '1' => __('yes')], 'type' => 'select', 'before_html' => '<h3>' . __('notification.email') . '</h3>'],
      'name'    => 'setting_email_pm',
    ], [
      'title'   => __('appeal.@'),
      'arr'     => ['options' => ['0' => __('no'), '1' => __('yes')], 'type' => 'select'],
      'name'    => 'setting_email_appealed',
    ]
];
