<?php foreach ($users as $user) { ?>
  <a class="flex relative p5 items-center hidden gray-600" href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
    <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'ava-sm'); ?>
    <?= $user['login']; ?>
  </a>
<?php } ?>