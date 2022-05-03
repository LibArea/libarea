<?php
/*
 * Setting up notifications.
 * Настройка уведомлений.
 */

return [
    [
      'title'   => __('app.message_PM'),
      'arr'     => ['options' => ['0' => __('app.no'), '1' => __('app.yes')], 'type' => 'select', 'before_html' => '<h3>' . __('app.notification_email') . '</h3>'],
      'name'    => 'setting_email_pm',
    ], [
      'title'   => __('app.appeal_@'),
      'arr'     => ['options' => ['0' => __('app.no'), '1' => __('app.yes')], 'type' => 'select'],
      'name'    => 'setting_email_appealed',
    ]
];
