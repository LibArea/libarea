<?php $day = Request::getGet('sort'); ?>

<span title="<?= __('app.top'); ?>" class="trigger gray-600">
  <span class="mb-none">
    <?php if ($day == 'TopMonth') : ?>
      <?= __('app.one_month'); ?>
    <?php elseif ($day == 'TopThreeMonths') : ?>
      <?= __('app.three_months'); ?>
    <?php elseif ($day == 'TopYear') : ?>
      <?= __('app.year'); ?>
    <?php elseif ($day == 'MostComments') : ?>
      <?= __('app.commented'); ?>
    <?php elseif ($day == 'Viewed') : ?>
      <?= __('app.viewed'); ?>
    <?php endif; ?>
  </span>
  <svg class="icons pointer gray-600">
    <use xlink:href="/assets/svg/icons.svg#sort-descending"></use>
  </svg>
</span>
<ul class="dropdown menu">
  <li<?php if ($day == 'TopMonth') : ?> class="active"<?php endif; ?>>
    <a class="gray-600 text-sm" href="?sort=TopMonth"><?= __('app.one_month'); ?></a>
  </li>
  <li<?php if ($day == 'TopThreeMonths') : ?> class="active"<?php endif; ?>>
    <a class="gray-600 text-sm" href="?sort=TopThreeMonths"><?= __('app.three_months'); ?></a>
  </li>
  <li<?php if ($day == 'TopYear') : ?> class="active"<?php endif; ?>>
    <a class="gray-600 text-sm" href="?sort=TopYear"><?= __('app.year'); ?></a>
  </li>
  <hr>
  <li<?php if ($day == 'MostComments') : ?> class="active"<?php endif; ?>>
    <a class="gray-600 text-sm" href="?sort=MostComments"><?= __('app.commented'); ?></a>
  </li>
  <li<?php if ($day == 'Viewed') : ?> class="active"<?php endif; ?>>
    <a class="gray-600 text-sm" href="?sort=Viewed"><?= __('app.viewed'); ?></a>
  </li>
</ul>