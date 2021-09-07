<?php
$pages = array(
  array('id' => 'edit-space', 'url' => '/space/edit/' . $data['space']['space_id'], 'content' => lang('Edit')),
  array('id' => 'logo', 'url' => '/space/logo/' . $data['space']['space_slug'] . '/edit', 'content' => lang('Logo') . ' / ' . lang('Cover art')),
);
echo tabs_nav($pages, $data['sheet'], $uid);
?>