<?php
  $pages = array(
    array('id' => 'edit', 'url' => '/space/edit/' . $data['space']['space_id'], 'content' => lang('edit'), 'icon' => 'bi bi-gear'),
    array('id' => 'logo', 'url' => '/space/logo/' . $data['space']['space_slug'] . '/edit', 'content' => lang('logo') . ' / ' . lang('cover art'), 'icon' => 'bi bi-camera'),
  );
  includeTemplate('/_block/tabs_nav', ['pages' => $pages, 'sheet' => $data['sheet'], 'user_id' => $uid['user_id']]); ?>
