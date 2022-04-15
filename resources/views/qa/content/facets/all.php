<main class="w-100">
  <div class="bg-violet box center">
    <h1 class="text-xl"><?= __($data['sheet']); ?></h1>
    <span class="text-sm gray-600">
      <?= __($data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="box-flex justify-between">
    <ul class="nav">

      <?= Tpl::insert(
        '/_block/navigation/nav',
        [
          'type' => $data['sheet'],
          'user' => $user,
          'list' => [
            [
              'id'    => $data['type'] . 's.all',
              'url'   => getUrlByName($data['type'] . 's.all'),
              'title' => __('all'),
              'icon'  => 'bi-app'
            ],
            [
              'id'    => $data['type'] . 's.new',
              'url'   => getUrlByName($data['type'] . 's.new'),
              'title' => __('new.ones'),
              'icon'  => 'bi-sort-up'
            ],
            [
              'tl'    => 1,
              'id'    => $data['type'] . 's.my',
              'url'   => getUrlByName($data['type'] . 's.my'),
              'title' => __('reading'),
              'icon'  => 'bi-check2-square'
            ],
          ]
        ]
      );
      ?>

    </ul>
    <?php if ($user['trust_level'] > 1) : ?>
      <p class="m0 text-xl">
        <?php if ($data['type'] == 'blog') : ?>
          <?php if ($data['limit']) : ?>
            <a class="ml15" title="<?= __('add'); ?>" href="<?= getUrlByName('content.add', ['type' => $data['type']]); ?>">
              <i class="bi-plus-lg middle"></i>
            </a>
          <?php endif; ?>
        <?php else : ?>
          <?php if (UserData::checkAdmin()) : ?>
            <a class="ml15" title="<?= __('add'); ?>" href="<?= getUrlByName('content.add', ['type' => $data['type']]); ?>">
              <i class="bi-plus-lg middle"></i>
            </a>
          <?php endif; ?>
        <?php endif; ?>
      </p>
    <?php endif; ?>
  </div>

  <div>
    <?php if (!empty($data['facets'])) : ?>
      <?php if ($data['type'] == 'blog') : ?>
        <?= Tpl::insert('/_block/facet/blog-list-all', ['facets' => $data['facets'], 'user' => $user]); ?>
      <?php else : ?>
        <div class="flex flex-wrap">
          <?= Tpl::insert('/_block/facet/topic-list-all', ['facets' => $data['facets'], 'user' => $user]); ?>
        </div>
      <?php endif; ?>
    <?php else : ?>
      <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.content'), 'icon' => 'bi-info-lg']); ?>
    <?php endif; ?>
  </div>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/' . $data['type'] . 's'); ?>
</main>