<?php $access = Access::trustLevels(config('trust-levels.tl_add_' . $data['type'])); ?>

<main>
  <div class="box bg-violet center">
    <h1 class="text-xl"><?= __('meta.' . $data['sheet'] . '_' . $data['type'] . 's'); ?></h1>
    <span class="gray-600">
      <?= __('meta.' . $data['sheet'] . '_' . $data['type'] . 's_info'); ?>.
    </span>
  </div>

  <div class="flex justify-between mb20">
    <ul class="nav">

      <?= insert(
        '/_block/navigation/nav',
        [
          'list' => [
            [
              'id'    => $data['type'] . '_all',
              'url'   => url($data['type'] . 's.all'),
              'title' => __('app.all'),
            ],
            [
              'id'    => $data['type'] . '_new',
              'url'   => url($data['type'] . 's.new'),
              'title' => __('app.new_ones'),
            ],
            [
              'tl'    => 1,
              'id'    => $data['type'] . '_my',
              'url'   => url($data['type'] . 's.my'),
              'title' => __('app.reading'),
            ],
          ]
        ]
      );
      ?>

    </ul>

    <?php if ($access) : ?>
      <?php if ($data['countUserFacet'] == 0 || UserData::checkAdmin()) : ?>
        <p class="m0 text-xl">
          <a class="ml15" title="<?= __('app.add'); ?>" href="<?= url('content.add', ['type' => $data['type']]); ?>">
            <i class="bi-plus-lg middle"></i>
          </a>
        </p>
      <?php endif; ?>
    <?php endif; ?>
  </div>

    <?php if (!empty($data['facets'])) : ?>
      <?php if ($data['type'] == 'blog') : ?>
        <?= insert('/_block/facet/blog-list-all', ['facets' => $data['facets']]); ?>
      <?php else : ?>
        <div class="flex justify-between gap-max flex-wrap">
          <?= insert('/_block/facet/topic-list-all', ['facets' => $data['facets']]); ?>
        </div>
      <?php endif; ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'bi-info-lg']); ?>
    <?php endif; ?>

  <?= Html::pagination($data['pNum'], $data['pagesCount'], false, url($data['type'] . 's.' . $data['sheet'])); ?>
</main>