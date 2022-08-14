<div class="item-cat">
  <?= insert('/_block/navigation/breadcrumbs', ['list' => $data['breadcrumb']]); ?>

  <h1 class="m0">
    <?= $data['category']['facet_title']; ?>
    <?php if (UserData::checkAdmin()) : ?>
      <a class="text-sm ml5" href="<?= url('content.edit', ['type' => 'category', 'id' => $data['category']['facet_id']]); ?>">
        <sup><svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#edit"></use>
          </svg></sup>
      </a>
      <a class="text-sm ml15" href="<?= url('admin.facets.type', ['type' => 'category']); ?>">
        <sup class="gray-600"><svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#tool"></use>
          </svg></sup>
      </a>
    <?php endif; ?>
  </h1>
</div>

<?php if ($data['childrens']) : ?>
  <div class="item-categories">
    <?php foreach ($data['childrens'] as $lt) : ?>
      <div>
        <a class="text-2xl" href="<?= url('category', ['sort' => $data['sort'], 'slug' => $lt['facet_slug']]); ?>">
          <?= $lt['facet_title']; ?>
        </a> <sup class="gray-600"><?= $lt['counts']; ?></sup>
        <?php if (UserData::checkAdmin()) : ?>
          <a class="ml5" href="<?= url('content.edit', ['type' => 'category', 'id' => $lt['facet_id']]); ?>">
            <sup><svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#edit"></use>
              </svg></i>
          </a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($data['low_matching']) : ?>
    <div class="ml20 mb20 mb-block">
      <?php foreach ($data['low_matching'] as $rl) : ?>
        <div class="inline mr20">
          <a class="text-2xl" href="<?= url('category', ['sort' => $data['sort'], 'slug' => $rl['facet_slug']]); ?>">
            @<?= $rl['facet_title']; ?>
          </a>
          <?php if (UserData::checkAdmin()) : ?>
            <a class="text-sm ml5" href="<?= url('category.edit', ['id' => $rl['facet_id']]); ?>">
              <sup class="gray-600"><svg class="icons">
                  <use xlink:href="/assets/svg/icons.svg#edit"></use>
                </svg> <small><?= $rl['facet_type']; ?></small></sup>
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
          <a class="text-2xl" href="<?= url('category', ['sort' => $data['sort'], 'slug' => $rl['facet_slug']]); ?>">
            @<?= $rl['facet_title']; ?>
          </a>
          <?php if (UserData::checkAdmin()) : ?>
            <a class="text-sm ml5" href="<?= url('category.edit', ['id' => $rl['facet_id']]); ?>">
              <sup class="gray-600"><svg class="icons">
                  <use xlink:href="/assets/svg/icons.svg#edit"></use>
                </svg> <small><?= $rl['facet_type']; ?></small></sup>
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
      <?= insert('/content/item/item-card', ['data' => $data, 'sort' => $data['sort']]); ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no'), 'icon' => 'info']); ?>
    <?php endif; ?>
    
    <?php $url = url('category', ['sort' => 'all', 'slug' => $data['category']['facet_slug']]); ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, $url . '/page'); ?>
  </main>
  <aside>
    <div class="box bg-beige max-w300"><?= Content::text($data['category']['facet_info'] ?? '', 'line'); ?></div>

    <?php if ($data['related_posts']) : ?>
      <div class="box bg-blue-200 max-w300">
        <h4 class="uppercase-box"><?= __('web.related_posts'); ?></h4>
        <ul>
        <?php foreach ($data['related_posts'] as $rp) : ?>
          <li class="mb15">
            <a href="<?= url('post', ['id' => $rp['id'], 'slug' => $rp['post_slug']]); ?>"><?= $rp['value']; ?></a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (UserData::checkActiveUser()) : ?>
      <div class="box bg-lightgray max-w300">
        <h4 class="uppercase-box"><?= __('web.menu'); ?></h4>
        <ul class="menu">
          <?= insert('/_block/navigation/item/menu', ['data' => $data]); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>