<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">
    <?= tabs_nav(
      'menu',
      $data['type'],
      $user,
      $pages = Config::get('menu.left'),
    ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12">
  <div class="box-flex-white">
    <ul class="flex flex-row list-none text-sm">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'tl'    => 0,
            'id'    => $data['type'] . '.all',
            'url'   => '/comments',
            'title' => Translate::get('comments'),
            'icon'  => 'bi bi-sort-down'
          ],
          [
            'tl'    => UserData::REGISTERED_ADMIN,
            'id'    => $data['type'] . '.deleted',
            'url'   => getUrlByName('comments.deleted'),
            'title' => Translate::get('deleted'),
            'icon'  => 'bi bi-app'
          ],
        ]
      ); ?>

    </ul>
    <div data-template="feed" class="tippy gray-400">
      <i class="bi bi-info-square"></i>
    </div>
    <div id="feed" style="display: none;">
      <div class="text-xm gray-600 p5 center"><?= Translate::get($data['sheet'] . '.info'); ?></div>
    </div>
  </div>

  <?php if (!empty($data['comments'])) { ?>
    <div class="box-white">
      <?= Tpl::import(
        '/content/comment/comment',
        [
          'answer'   => $data['comments'],
          'user'     => $user,
        ]
      ); ?>
    </div>
    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

  <?php } else { ?>
    <?= no_content(Translate::get('no.comments'), 'bi bi-info-lg'); ?>
  <?php } ?>
</main>
<?= Tpl::import('/_block/sidebar/lang', ['lang' => Translate::get('comments-desc')]); ?>