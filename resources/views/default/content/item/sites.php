<div class="item-cat">
  <?= insert('/_block/navigation/breadcrumbs', ['list' => $data['breadcrumb']]); ?>

  <h1>
    <?= $data['category']['facet_title']; ?>
    <?php if (UserData::checkAdmin()) : ?>
      <a class="text-sm ml5" href="<?= url('content.edit', ['type' => 'category', 'id' => $data['category']['facet_id']]); ?>">
        <sup><svg class="icons"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg></sup>
      </a>
      <a class="text-sm ml15" href="<?= url('admin.category.structure'); ?>">
        <sup class="gray-600">fs</sup>
      </a>
      <small class="text-sm gray-600"><sup><?= $data['category']['facet_type']; ?></sup></small>
    <?php endif; ?>
  </h1>
</div>

<?php if ($data['childrens']) : ?>
  <div class="item-categories">
    <?php foreach ($data['childrens'] as $lt) : ?>
      <div>
        <a class="text-2xl" href="<?= url('web.dir', ['grouping' => $data['screening'], 'slug' => $lt['facet_slug']]); ?>">
          <?= $lt['facet_title']; ?>
        </a> <sup class="gray-600"><?= $lt['counts']; ?></sup>
        <?php if (UserData::checkAdmin()) : ?>
          <a class="ml5" href="<?= url('content.edit', ['type' => 'category', 'id' => $lt['facet_id']]); ?>">
            <sup><svg class="icons"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg></i>
          </a>
          <small class="text-sm gray-600"><sup><?= $lt['facet_type']; ?></sup></small>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($data['low_matching']) : ?>
    <div class="ml20 mb20 mb-block">
      <?php foreach ($data['low_matching'] as $rl) : ?>
        <div class="inline mr20">
          <a class="text-2xl" href="<?= url('web.dir', ['grouping' => $data['screening'], 'slug' => $rl['facet_slug']]); ?>">
            @<?= $rl['facet_title']; ?>
          </a>
          <?php if (UserData::checkAdmin()) : ?>
            <a class="text-sm ml5" href="<?= url('category.edit', ['id' => $rl['facet_id']]); ?>">
              <sup class="gray-600"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg> <small><?= $rl['facet_type']; ?></small></sup>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

<?php else : ?>

  <?php if ($data['low_matching']) : ?>
    <div class="item-categories mb-block">
      <?php foreach ($data['low_matching'] as $rl) : ?>
        <div class="inline mr20">
          <a class="text-2xl" href="<?= url('web.dir', ['grouping' => $data['screening'], 'slug' => $rl['facet_slug']]); ?>">
            @<?= $rl['facet_title']; ?>
          </a>
          <?php if (UserData::checkAdmin()) : ?>
            <a class="text-sm ml5" href="<?= url('category.edit', ['id' => $rl['facet_id']]); ?>">
              <sup class="gray-600"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg> <small><?= $rl['facet_type']; ?></small></sup>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

<div id="contentWrapper">
  <main>
    <?= insert('/_block/navigation/item/nav', ['data' => $data]); ?>

    <?php if (!empty($data['items'])) : ?>
      <?= insert('/content/item/site', ['data' => $data, 'screening' => $data['screening']]); ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no'), 'icon' => 'info']); ?>
    <?php endif; ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/web/' . $data['screening']); ?>
  </main>
  <aside>
    <div class="box bg-beige mt15"><?= Content::text($data['category']['facet_info'] ?? '', 'line'); ?></div>
    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box text-sm bg-lightgray mt15">
        <h3 class="uppercase-box"><?= __('web.menu'); ?></h3>
        <ul class="menu">
          <?= insert('/_block/navigation/item/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>