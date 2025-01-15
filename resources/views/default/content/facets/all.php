<main class="w-100">
  <div class="box">
    <div class="mb15">
      <h1 class="title"><?= __('meta.' . $data['sheet'] . '_' . $data['type']); ?></h1>
      <span class="gray-600 text-sm">
        <?= __('meta.' . $data['sheet'] . '_' . $data['type'] . '_info'); ?>.
      </span>
    </div>

    <div class="nav-bar">
      <ul class="nav scroll-menu">

        <?= insert(
          '/_block/navigation/nav',
          [
            'list' => [
              [
                'id'    => $data['type'] . '_all',
                'url'   => url($data['type'] . '.all'),
                'title' => 'app.all',
              ],
              [
                'id'    => $data['type'] . '_new',
                'url'   => url($data['type'] . '.new'),
                'title' => 'app.new_ones',
              ],
              [
                'tl'    => 1,
                'id'    => $data['type'] . '_my',
                'url'   => url($data['type'] . '.my'),
                'title' => 'app.reading',
              ],
            ]
          ]
        );
        ?>

      </ul>

	  <?php $type =  $data['type'] === 'blogs' ? 'blog' : 'topic'; ?>
      <?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_' . $type))) : ?>
        <?php if ($data['countUserFacet'] == 0 || $container->user()->admin()) : ?>
          <p class="text-xl">
            <a class="btn btn-outline-primary btn-small" href="<?= url('facet.form.add', ['type' => $type]); ?>">
              <svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#plus"></use>
              </svg>
              <?= __('app.add'); ?>
            </a>
          </p>
        <?php endif; ?>
      <?php endif; ?>
    </div>

    <?php if (!empty($data['facets'])) : ?>
      <?php if ($type == 'blog') : ?>
        <div class="mb20">
          <?= insert('/_block/facet/blog-list-all', ['facets' => $data['facets']]); ?>
        </div>
      <?php else : ?>
        <div class="flex justify-between gap-lg flex-wrap mb20">
          <?= insert('/_block/facet/topic-list-all', ['facets' => $data['facets']]); ?>
        </div>
      <?php endif; ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_content'), 'icon' => 'info']); ?>
    <?php endif; ?>

    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, url($data['type'] . '.' . $data['sheet'])); ?>
  </div>
</main>