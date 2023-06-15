<?php

use Hleb\Constructor\Handlers\Request; ?>

<div class="item-cat">
  <?= insert('/_block/navigation/breadcrumbs', ['list' => $data['breadcrumb']]);
  $grouping = Request::get('grouping');
  ?>
  <div class="right"><?= insert('/content/item/characteristics', ['data' => $data, 'grouping' => $grouping]); ?></div>
  <h1 class="m0 flex gap">
    <?= $data['category']['facet_title']; ?>
    <?php if (UserData::checkAdmin()) : ?>
      <div class="flex gap text-xs">
        <a class="gray-600" href="<?= url('content.edit', ['type' => 'category', 'id' => $data['category']['facet_id']]); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#edit"></use>
          </svg>
        </a>
        <a class="gray-600" href="<?= url('admin.facets.type', ['type' => 'category']); ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#tool"></use>
          </svg>
        </a>
      </div>
    <?php endif; ?>
  </h1>
</div>

<?php if ($data['childrens']) : ?>
  <div class="item-categories">
    <?php foreach ($data['childrens'] as $lt) : ?>
      <?php $url_cat = $grouping ? url('grouping.category', ['grouping' => $grouping, 'sort' => $data['sort'], 'slug' => $lt['facet_slug']]) : url('category', ['sort' => $data['sort'], 'slug' => $lt['facet_slug']]); ?>
      <div>
        <a class="text-2xl" href="<?= $url_cat; ?>">
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

<div id="contentWrapper" class="wrap wrap-max">
  <main class="w-100">
    <?= insert('/_block/navigation/item/nav', ['data' => $data]); ?>

    <?php if (!empty($data['items'])) : ?>
      <?= insert('/content/item/item-card', ['data' => $data, 'sort' => $data['sort']]); ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('web.no'), 'icon' => 'info']); ?>
    <?php endif; ?>

    <?php $url = url('category', ['sort' => 'all', 'slug' => $data['category']['facet_slug']]); ?>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, $url); ?>
  </main>
  <aside>
    <div class="box bg-beige max-w300"><?= markdown($data['category']['facet_info'] ?? '', 'line'); ?></div>

    <?php if ($data['related_posts']) : ?>
      <div class="box bg-blue-200 max-w300">
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