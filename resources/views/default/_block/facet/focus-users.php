<?php if ($focus_users) { ?>
  <div class="text-sm mt20 gray-600">
    <div class="uppercase inline mr5"><?= Translate::get('reads'); ?>:</div>
    <?php $n = 0;
    foreach ($focus_users as $user) {
      $n++; ?>
      <a class="-mr-1" href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
        <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'ava-sm'); ?>
      </a>
    <?php } ?>
    <?php if ($n > 5) { ?><span class="ml10">...</span><?php } ?>
    <span class="focus-user gray-400 ml10">
      <?= $topic_focus_count; ?>
    </span>
  </div>
<?php } ?>