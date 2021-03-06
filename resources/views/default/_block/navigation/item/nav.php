<?php
$category = $data['category'] ?? null;
if ($category) : ?>
  <div class="flex justify-between tems-center  mr20">
  <h2 class="inline m0"><?= Html::numWord($data['count'], __('web.num_website'), false); ?>: <?= $data['count']; ?></h2>
  <ul class="nav">
    <li<?php if ($data['sheet'] == 'all') { ?> class="active" <?php } ?>>
      <a href="<?= url('web.dir.all', ['grouping' => 'all', 'slug' => $category['facet_slug']]); ?>">
        <?= __('web.by_date'); ?>
      </a>
      </li>
      <li<?php if ($data['sheet'] == 'top') { ?> class="active" <?php } ?>>
        <a href="<?= url('web.dir.top', ['grouping' => 'all', 'slug' => $category['facet_slug']]); ?>">
          TOP
        </a>
        </li>
  </ul>
  </div>
<?php endif; ?>