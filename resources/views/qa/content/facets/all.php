<main class="col-span-12 mb-col-12">
  <div class="box-white bg-violet-50 center">
    <h1 class="text-xl"><?= Translate::get($data['sheet']); ?></h1>
    <span class="text-sm gray-600">
      <?= Translate::get($data['sheet'] . '.info'); ?>.
    </span>
  </div>

  <div class="mb15">
    <ul class="box-flex list-none text-sm">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'id'    => $data['type'] . 's.all',
            'url'   => getUrlByName($data['type'] . 's.all'),
            'title' => Translate::get('all'),
            'icon'  => 'bi bi-app'
          ],
          [
            'id'    => $data['type'] . 's.new',
            'url'   => getUrlByName($data['type'] . 's.new'),
            'title' => Translate::get('new ones'),
            'icon'  => 'bi bi-sort-up'
          ],
          [
            'tl'    => 1,
            'id'    => $data['type'] . 's.my',
            'url'   => getUrlByName($data['type'] . 's.my'),
            'title' => Translate::get('reading'),
            'icon'  => 'bi bi-check2-square'
          ],
        ]
      );
      ?>
      
        <?php if ($user['trust_level'] > 1) { ?>
        <li class="right">
          <?php if ($data['limit']) { ?>
            <a class="ml15" title="<?= Translate::get('add'); ?>" href="<?= getUrlByName($data['type'] . '.add'); ?>">
              <i class="bi bi-plus-lg middle"></i>
            </a>
         <?php } ?>
        </li>
        <?php } ?>
    </ul>
  </div>

  <div class="p15">
    <?php if (!empty($data['facets'])) { ?>
      <?php if ($data['type'] == 'blog') { ?>
        <?= Tpl::import('/_block/facet/blog-list-all', ['facets' => $data['facets'], 'user' => $user]); ?>
      <?php } else { ?>
        <?= Tpl::import('/_block/facet/topic-list-all', ['facets' => $data['facets'], 'user' => $user]); ?>
      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no.content'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/' . $data['type'] . 's'); ?>
</main>