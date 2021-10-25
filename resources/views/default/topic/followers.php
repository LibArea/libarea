<div class="bg-white p15">
  <?php foreach ($users as $user) { ?>
    <a class="flex relative pb15 items-center hidden gray-light" href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>">
      <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'w44 br-rd-50 mr10'); ?>
      <?= $user['user_login']; ?>
    </a>
  <?php } ?>
</div>