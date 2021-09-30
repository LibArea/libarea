<?php $pages = array(
  array('id' => 'settings', 'url' => getUrlByName('setting', ['login' => $uid['user_login']]), 'content' => lang('settings'), 'icon' => 'icon-cog-outline'),
  array('id' => 'avatar', 'url' => getUrlByName('setting.avatar', ['login' => $uid['user_login']]), 'content' => lang('avatar'), 'icon' => 'icon-smile'),
  array('id' => 'security', 'url' => getUrlByName('setting.security', ['login' => $uid['user_login']]), 'content' => lang('password'), 'icon' => 'icon-wrench'),
  array('id' => 'notifications', 'url' => getUrlByName('setting.notifications', ['login' => $uid['user_login']]), 'content' => lang('notifications'), 'icon' => 'icon-lightbulb'),
);
includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
