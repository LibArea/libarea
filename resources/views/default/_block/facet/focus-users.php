<?php if ($focus_users) { ?>
  <div class="size-14 mt20 gray-light-2">
    <div class="uppercase inline mr5"><?= Translate::get('reads'); ?>:</div>
    <?php $n = 0;
    foreach ($focus_users as $user) {
      $n++; ?>
      <a class="-mr-1 bg-white" href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>">
        <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'w24 br-rd-50 br-box-gray'); ?>
      </a>
    <?php } ?>
    <?php if ($n > 5) { ?><span class="ml10">...</span><?php } ?>
    <span class="focus-user blue ml10">
      <?= $topic_focus_count; ?>
    </span>
  </div>
<?php } ?>