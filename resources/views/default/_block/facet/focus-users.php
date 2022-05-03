<?php if ($focus_users) : ?>
  <span class="text-sm mt20 gray-600">
    <div class="uppercase inline mr5"><?= __('app.reads'); ?>:</div>
    <?php $n = 0;
    foreach ($focus_users as $user) :
      $n++; ?>
      <a class="-mr-1" href="<?= url('profile', ['login' => $user['login']]); ?>">
        <?= Html::image($user['avatar'], $user['login'], 'ava-sm', 'avatar', 'max'); ?>
      </a>
    <?php endforeach; ?>
    <?php if ($n > 5) : ?><span class="ml10">...</span><?php endif; ?>
    <span class="focus-user gray-600 ml10">
      <?= $topic_focus_count; ?>
    </span>
  </span>
<?php endif; ?>