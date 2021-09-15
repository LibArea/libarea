<?php
$pages = array(
  array('id' => 'setting', 'url' => getUrlByName('setting', ['login' => $uid['user_login']]), 'content' => lang('Setting profile')),
  array('id' => 'avatar', 'url' => getUrlByName('setting-avatar', ['login' => $uid['user_login']]), 'content' => lang('Avatar')),
  array('id' => 'security', 'url' => getUrlByName('setting-security', ['login' => $uid['user_login']]), 'content' => lang('Password')),
  array('id' => 'notifications', 'url' => getUrlByName('setting-notifications', ['login' => $uid['user_login']]), 'content' => lang('Notifications')),
);
echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
?>