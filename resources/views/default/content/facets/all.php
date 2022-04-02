<main class="col-two">
  <div class="box-white center">
    <h1 class="text-xl"><?= Translate::get($data['sheet']); ?></h1>
    <span class="text-sm gray-600">
      <?= Translate::get($data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="box-flex-white">
    <ul class="nav">

      <?= Tpl::import(
        '/_block/navigation/nav',
        [
          'type' => $data['sheet'],
          'user' => $user,
          'list' => [
            [
              'id'    => $data['type'] . 's.all',
              'url'   => getUrlByName($data['type'] . 's.all'),
              'title' => Translate::get('all'),
              'icon'  => 'bi-app'
            ],
            [
              'id'    => $data['type'] . 's.new',
              'url'   => getUrlByName($data['type'] . 's.new'),
              'title' => Translate::get('new ones'),
              'icon'  => 'bi-sort-up'
            ],
            [
              'tl'    => 1,
              'id'    => $data['type'] . 's.my',
              'url'   => getUrlByName($data['type'] . 's.my'),
              'title' => Translate::get('reading'),
              'icon'  => 'bi-check2-square'
            ],
          ]
        ]
      );
      ?>

    </ul>
    <?php if ($user['trust_level'] > 1) { ?>
      <p class="m0 text-xl">
        <?php if ($data['type'] == 'blog') { ?>
          <?php if ($data['limit']) { ?>
            <a class="ml15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('content.add', ['type' => $data['type']]); ?>">
              <i class="bi-plus-lg middle"></i>
            </a>
          <?php } ?>
        <?php } else { ?>
          <?php if (UserData::checkAdmin()) { ?>
            <a class="ml15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName('content.add', ['type' => $data['type']]); ?>">
              <i class="bi-plus-lg middle"></i>
            </a>
          <?php } ?>
        <?php } ?>
      </p>
    <?php } ?>
  </div>

  <div class="box-white">
    <?php if (!empty($data['facets'])) { ?>
      <?php if ($data['type'] == 'blog') { ?>
        <?= Tpl::import('/_block/facet/blog-list-all', ['facets' => $data['facets'], 'user' => $user]); ?>
      <?php } else { ?>
        <div class="flex flex-wrap">
          <?= Tpl::import('/_block/facet/topic-list-all', ['facets' => $data['facets'], 'user' => $user]); ?>
        </div>
      <?php } ?>
    <?php } else { ?>
      <?= Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.content'), 'icon' => 'bi-info-lg']); ?>
    <?php } ?>
  </div>
  <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/' . $data['type'] . 's'); ?>
</main>