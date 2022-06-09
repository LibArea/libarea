<?php
$category = $data['category'] ?? null;
if ($category) : ?>

  <?= Html::numWord($data['count'], __('web.num_website'), false); ?>: <?= $data['count']; ?>
  <ul class="nav right">
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
<?php endif; ?>