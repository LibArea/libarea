<?php if ($focus_users) : ?>
  <span class="text-sm mt20">
    <div class="uppercase inline  gray-600 mr5"><?= __('app.reads'); ?>:</div>
    <?php $n = 0;
    foreach ($focus_users as $user) :
      $n++; ?>
      <a class="-mr-1" href="<?= url('profile', ['login' => $user['login']]); ?>">
        <?= Html::image($user['avatar'], $user['login'], 'img-sm', 'avatar', 'max'); ?>
      </a>
    <?php endforeach; ?>
    <?php if ($n > 5) : ?><span class="ml10">...</span><?php endif; ?>
    <?php if (!empty($topic_focus_count)) : ?>
    <span class="focus-user ml10 sky">
      <?= $topic_focus_count; ?>
    </span>
    <?php endif; ?>
  </span>
<?php endif; ?>