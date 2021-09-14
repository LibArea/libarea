<?php
$pages = array(
  array('id' => 'edit-space', 'url' => '/space/edit/' . $data['space']['space_id'], 'content' => lang('Edit')),
  array('id' => 'logo', 'url' => '/space/logo/' . $data['space']['space_slug'] . '/edit', 'content' => lang('Logo') . ' / ' . lang('Cover art')),
);
echo returnBlock('tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]);
?>