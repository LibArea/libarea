<?php
  $pages = array(
    array('id' => 'edit', 'url' => '/space/edit/' . $data['space']['space_id'], 'content' => lang('edit'), 'icon' => 'icon-cog-outline'),
    array('id' => 'logo', 'url' => '/space/logo/' . $data['space']['space_slug'] . '/edit', 'content' => lang('logo') . ' / ' . lang('cover art'), 'icon' => 'icon-upload-cloud-outline'),
  );
  includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]); ?>
