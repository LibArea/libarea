<?php
$category = $data['category'] ?? null;
if ($category) : ?>

  <p>
    <?= Html::numWord($data['count'], __('web.num_website'), false); ?>: <?= $data['count']; ?>
    <span class="right mr30">
      <a class="tabs<?php if ($data['sheet'] == 'all') { ?> active<?php } ?>" href="<?= url('web.dir.all', ['grouping' => 'all', 'slug' => $category['facet_slug']]); ?>">
        <?= __('web.by_date'); ?>
      </a>
      <a class="tabs<?php if ($data['sheet'] == 'top') { ?> active<?php } ?>" href="<?= url('web.dir.top', ['grouping' => 'all', 'slug' => $category['facet_slug']]); ?>">
        TOP
      </a>
    </span>
  </p>
<?php else : ?>
  <div class="flex justify-between items-center mb15">
    <h2 class="lfet inline"><?= __($data['sheet'] . '.view'); ?></h2>
    <div class="mr30">
      <a class="tabs<?php if ($data['sheet'] == 'main') : ?> active<?php endif; ?>" href="<?= url('web'); ?>">
        <?= __('web.by_date'); ?>
      </a>
      <a class="tabs<?php if ($data['sheet'] == 'top') : ?> active<?php endif; ?>" href="<?= url('web.top'); ?>">
        TOP
      </a>
    </div>
  </div>
<?php endif; ?>