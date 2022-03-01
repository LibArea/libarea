<?php
$category = $data['category'] ?? null;
if ($category) { ?>

  <p>
    <?= num_word($data['count'], Translate::get('num-website'), false); ?>: <?= $data['count']; ?>
    <span class="right mr30">
      <a class="tabs<?php if ($data['sheet'] == 'web.all') { ?> active<?php } ?>" href="<?= getUrlByName('web.dir.all', ['cat' => 'cat', 'slug' => $category['facet_slug']]); ?>">
        <?= Translate::get('by.date'); ?>
      </a>
      <a class="tabs<?php if ($data['sheet'] == 'web.top') { ?> active<?php } ?>" href="<?= getUrlByName('web.dir.top', ['cat' => 'cat', 'slug' => $category['facet_slug']]); ?>">
        TOP
      </a>
    </span>
  </p>
<?php } else { ?>
  <div class="flex justify-between items-center mb15">
    <h2 class="lfet inline"><?= Translate::get($data['sheet'] . '.view'); ?></h2>
    <div class="mr30">
      <a class="tabs<?php if ($data['sheet'] == 'web') { ?> active<?php } ?>" href="<?= getUrlByName('web'); ?>">
        <?= Translate::get('by.date'); ?>
      </a>
      <a class="tabs<?php if ($data['sheet'] == 'web.top') { ?> active<?php } ?>" href="<?= getUrlByName('web.top'); ?>">
        TOP
      </a>
    </div>
  </div>
<?php } ?>