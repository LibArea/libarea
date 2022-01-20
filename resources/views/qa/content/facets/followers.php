<div class="bg-white p15">
  <?php foreach ($users as $user) { ?>
    <a class="flex relative pb15 items-center hidden gray-600" href="/@<?= $user['login']; ?>">
      <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'w40 h40 br-rd-50 mr10'); ?>
      <?= $user['login']; ?>
    </a>
  <?php } ?>
</div>