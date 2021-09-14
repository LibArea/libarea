<?php
$pages = array(
  array('id' => 'setting', 'url' => '/u/' . $uid['user_login'] . '/setting', 'content' => lang('Setting profile')),
  array('id' => 'avatar', 'url' => '/u/' . $uid['user_login'] . '/setting/avatar', 'content' => lang('Avatar')),
  array('id' => 'security', 'url' => '/u/' . $uid['user_login'] . '/setting/security', 'content' => lang('Password')),
  array('id' => 'notifications', 'url' => '/u/' . $uid['user_login'] . '/setting/notifications', 'content' => lang('Notifications')),
);
echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
?>