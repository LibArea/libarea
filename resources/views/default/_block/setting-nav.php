<?php
$pages = [
  [
    'id' => 'settings',
    'url' => getUrlByName('setting', ['login' => $uid['user_login']]),
    'content' => lang('settings'),
    'icon' => 'bi bi-gear'
  ], [
    'id' => 'avatar',
    'url' => getUrlByName('setting.avatar', ['login' => $uid['user_login']]),
    'content' => lang('avatar'),
    'icon' => 'bi bi-emoji-smile'
  ], [
    'id' => 'security',
    'url' => getUrlByName('setting.security', ['login' => $uid['user_login']]),
    'content' => lang('password'),
    'icon' => 'bi bi-lock'
  ], [
    'id' => 'notifications',
    'url' => getUrlByName('setting.notifications', ['login' => $uid['user_login']]),
    'content' => lang('notifications'),
    'icon' => 'bi bi-app-indicator'
  ]
];

includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
