<?php foreach ($users as $user) { ?>
  <a class="flex relative p5 items-center hidden gray-600" href="/@<?= $user['login']; ?>">
    <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'w20 h20 br-rd-50 mr5'); ?>
    <?= $user['login']; ?>
  </a>
<?php } ?>
 