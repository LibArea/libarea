<?= insertTemplate('header', ['meta' => $meta, 'category' => $data['category']]); ?>

<div class="item-cat wrap">
  <?= insert('/_block/navigation/breadcrumbs', ['list' => $data['breadcrumb']]);
  $grouping = $container->request()->param('grouping')->value();
  ?>
  <div class="right mb-none"><?= insertTemplate('characteristics', ['data' => $data, 'grouping' => $grouping]); ?></div>
  <h1 class="m0 flex gap">
    <?= $data['category']['facet_title']; ?>
    <?php if ($container->user()->admin()) : ?>
      <div class="flex gap">
        <a class="gray-600" href="<?= url('facet.form.edit', ['type' => 'category', 'id' => $data['category']['facet_id']]); ?>">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#edit"></use>
          </svg>
        </a>
        <a class="gray-600" href="<?= url('admin.facets.type', ['type' => 'category']); ?>">
          <svg class="icon">
            <use xlink:href="/assets/svg/icons.svg#tool"></use>
          </svg>
        </a>
      </div>
    <?php endif; ?>
  </h1>
</div>

<?php if ($data['childrens']) : ?>
  <div class="item-categories wrap">
    <?php foreach ($data['childrens'] as $lt) : ?>
      <?php $url_cat = $grouping ? url('grouping.category', ['grouping' => $grouping, 'sort' => $data['sort'], 'slug' => $lt['facet_slug']]) : url('category', ['sort' => $data['sort'], 'slug' => $lt['facet_slug']]); ?>
      <div>
        <a class="text-2xl" href="<?= $url_cat; ?>">
          <?= $lt['facet_title']; ?>
        </a> <sup class="gray-600"><?= $lt['counts']; ?></sup>
        <?php if ($container->user()->admin()) : ?>
          <a class="ml5 gray-600" href="<?= url('facet.form.edit', ['type' => 'category', 'id' => $lt['facet_id']]); ?>">
            <sup><svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#edit"></use>
              </svg></sup>
          </a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

    <?php if ($data['low_matching']) : ?>
      <?php foreach ($data['low_matching'] as $rl) : ?>
        <div>
          <a class="text-2xl ml10" href="<?= url('category', ['sort' => $data['sort'], 'slug' => $rl['facet_slug']]); ?>">
            @<?= $rl['facet_title']; ?>
          </a>
          <?php if ($container->user()->admin()) : ?>
            <a class="text-sm ml5" href="<?= url('facet.form.edit', ['type' => 'category', 'id' => $rl['facet_id']]); ?>">
              <sup class="gray-600"><svg class="icon">
                  <use xlink:href="/assets/svg/icons.svg#edit"></use>
				</svg></sup>   
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

<?php else : ?>

  <?php if ($data['low_matching']) : ?>
    <div class="item-categories mb-block wrap">
      <?php foreach ($data['low_matching'] as $rl) : ?>
        <div class="inline mr20">
          <a class="text-2xl" href="<?= url('category', ['sort' => $data['sort'], 'slug' => $rl['facet_slug']]); ?>">
            @<?= $rl['facet_title']; ?>
          </a>
          <?php if ($container->user()->admin()) : ?>
            <a class="text-sm ml5" href="<?= url('facet.form.edit', ['type' => 'category', 'id' => $rl['facet_id']]); ?>">
              <sup class="gray-600"><svg class="icon">
                  <use xlink:href="/assets/svg/icons.svg#edit"></use>
                </svg> <small><?= $rl['facet_type']; ?></small></sup>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?php endif; ?>

<div id="contentWrapper" class="wrap">
  <main>
    <div class="box">
      <?= insertTemplate('/navigation/nav', ['data' => $data]); ?>

      <?php if (!empty($data['items'])) : ?>
        <?= insertTemplate('/item/card', ['data' => $data, 'sort' => $data['sort']]); ?>
      <?php else : ?>
        <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no'), 'icon' => 'info']); ?>
      <?php endif; ?>

      <?php $url = url('category', ['sort' => 'all', 'slug' => $data['category']['facet_slug']]); ?>
      <?= Html::pagination($data['pNum'], $data['pagesCount'], false, $url); ?>
    </div>
  </main>
  <aside>
    <div class="box gray"><?= markdown($data['category']['facet_info'] ?? '', 'line'); ?></div>

    <?php if ($data['related_posts']) : ?>
      <div class="box bg-yellow">
        <h4 class="uppercase-box"><?= __('web.related_posts'); ?></h4>
        <ul>
          <?php foreach ($data['related_posts'] as $rp) : ?>
            <li class="mb15">
              <a href="<?= post_slug($rp['id'], $rp['post_slug']); ?>"><?= $rp['value']; ?></a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if ($container->user()->active()) : ?>
      <div class="box">
        <h4 class="uppercase-box"><?= __('web.menu'); ?></h4>
        <ul class="menu">
          <?= insertTemplate('/navigation/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>

<?= insertTemplate('footer'); ?>