<?php if ($focus_users) : ?>
    <div class="uppercase inline gray-600 mr5"><?= __('app.reads'); ?>:</div>
    <?php $n = 0;
    foreach ($focus_users as $user) :
      $n++; ?>
      <a class="-mr-1" href="<?= url('profile', ['login' => $user['login']]); ?>">
        <?= Html::image($user['avatar'], $user['login'], 'img-sm', 'avatar', 'max'); ?>
      </a>
    <?php endforeach; ?>
    <?php if ($n > 5) : ?><span class="ml10">...</span><?php endif; ?>
    <?php if (!empty($facet['facet_focus_count'])) : ?>
      <span data-id="<?= $facet['facet_id']; ?>" data-slug="<?= $facet['facet_slug']; ?>" class="focus-user trigger ml10 sky">
        <?= $facet['facet_focus_count']; ?>
      </span>
      <div class="dropdown list_<?= $facet['facet_id']; ?>"></div>
    <?php endif; ?>
<?php endif; ?>