<main class="w-100">
  <div class="mb15">
    <h1 class="text-xl mt5 m0"><?= __('meta.' . $data['sheet'] . '_' . $data['type'] . 's'); ?></h1>
    <span class="gray-600 text-xs">
      <?= __('meta.' . $data['sheet'] . '_' . $data['type'] . 's_info'); ?>.
    </span>
  </div>

  <div class="flex justify-between mb20">
    <ul class="nav scroll">

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

    <?php if (Access::trustLevels(config('trust-levels.tl_add_' . $data['type']))) : ?>
      <?php if ($data['countUserFacet'] == 0 || UserData::checkAdmin()) : ?>
        <p class="text-xl">
          <a class="btn btn-outline-primary btn-small" href="<?= url('content.add', ['type' => $data['type']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#plus"></use>
            </svg>
            <?= __('app.add'); ?>
          </a>
        </p>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <?php if (!empty($data['facets'])) : ?>
    <?php if ($data['type'] == 'blog') : ?>
      <div class="mb20">
        <?= insert('/_block/facet/blog-list-all', ['facets' => $data['facets']]); ?>
      </div>
    <?php else : ?>
      <div class="flex justify-between gap-max flex-wrap mb20">
        <?= insert('/_block/facet/topic-list-all', ['facets' => $data['facets']]); ?>
      </div>
    <?php endif; ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'info']); ?>
  <?php endif; ?>

  <?= Html::pagination($data['pNum'], $data['pagesCount'], false, url($data['type'] . 's.' . $data['sheet'])); ?>
</main>