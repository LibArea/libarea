<?php if (count($subsections) > 1) : ?>
  <div class="p15 bg-beige mt15">
    <h4 class="uppercase-box"><?= __('app.subsections'); ?></h3>
      <?php foreach ($subsections as $site) : ?>
        <div class="mb15<?php if ($site['item_id'] == $item_id) : ?>  bg-white p5-10<?php endif; ?>">
          <a href="<?= url('website', ['id' => $site['item_id'], 'slug' => $site['item_slug']]); ?>"><?= $site['item_title']; ?></a>
          <?= Html::facets($site['facet_list'], 'category', 'tag-violet mr15'); ?>
          <?php if ($site['item_id'] != $item_id) : ?>
            <a href="<?= url('item.form.edit', ['id' => $site['item_id']]); ?>">
              <svg class="icon gray-600">
                <use xlink:href="/assets/svg/icons.svg#edit"></use>
              </svg>
            </a>
          <?php endif; ?>
          <div class="green"><?= $site['item_url']; ?> <span class="gray-600 ml20">(<?= $site['item_domain']; ?>)</span></div>
        </div>
      <?php endforeach; ?>
  </div>
<?php endif; ?>