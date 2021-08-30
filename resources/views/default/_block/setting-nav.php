<?php
$pages = array(
  array('id' => 'setting', 'url' => '/u/' . $uid['user_login'] . '/setting', 'content' => lang('Setting profile')),
  array('id' => 'avatar', 'url' => '/u/' . $uid['user_login'] . '/setting/avatar', 'content' => lang('Avatar')),
  array('id' => 'security', 'url' => '/u/' . $uid['user_login'] . '/setting/security', 'content' => lang('Password')),
  array('id' => 'notifications', 'url' => '/u/' . $uid['user_login'] . '/setting/notifications', 'content' => lang('Notifications')),
);
?>
<ul class="nav-tabs list-none mt0 pt0 mb15">
  <?php foreach ($pages as $page) { ?>
    <?php if ($page['id'] == $data['sheet']) { ?>
      <li class="active">
        <span><?= $page['content']; ?></span>
      </li>
    <?php } else { ?>
      <li>
        <a href="<?= $page['url']; ?>"><span><?= $page['content']; ?></span></a>
      </li>
    <?php } ?>
  <?php } ?>
</ul>