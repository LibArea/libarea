<?php
$category = $data['category'] ?? null;
if ($category) : ?>
  <div class="flex justify-between tems-center mr20 mb15">
    <h2 class="inline m0"><?= $data['count']; ?> <span class="lowercase"><?= Html::numWord($data['count'], __('web.num_website'), false); ?></spam></h2>
    <ul class="nav">
      <li<?php if ($data['sort'] == 'all') { ?> class="active" <?php } ?>>
        <a href="<?= url('category', ['sort' => 'all', 'slug' => $category['facet_slug']]); ?>">
          <?= __('web.by_date'); ?>
        </a>
        </li>
        <li<?php if ($data['sort'] == 'top') { ?> class="active" <?php } ?>>
          <a href="<?= url('category', ['sort' => 'top', 'slug' => $category['facet_slug']]); ?>">
            <?= __('web.top'); ?>
          </a>
          </li>
    </ul>
  </div>
<?php endif; ?>