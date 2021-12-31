<div class="col-span-2 justify-between no-mob">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get($data['sheet']); ?></p>
    <ul class="flex flex-row list-none m0 p0 center">

      <?= tabs_nav(
        $uid['user_id'],
        $data['sheet'],
        $pages = [
          [
            'id' => 'favorites',
            'url' => getUrlByName('favorites', ['login' => $uid['user_login']]),
            'content' => Translate::get('favorites'),
            'icon' => 'bi bi-bookmark'
          ],
          [
            'id' => 'subscribed',
            'url' => getUrlByName('user.subscribed', ['login' => $uid['user_login']]),
            'content' => Translate::get('subscribed'),
            'icon' => 'bi bi-bookmark-plus'
          ],
        ]
      ); ?>

    </ul>
  </div>
  <div class="bg-white br-rd5 br-box-gray br-rd5 p15">
    <?php if (!empty($data['drafts'])) { ?>
      <?php foreach ($data['drafts'] as $draft) { ?>

        <a href="<?= getUrlByName('post', ['id' => $draft['post_id'], 'slug' => $draft['post_slug']]); ?>">
          <h3 class="m0 text-2xl"><?= $draft['post_title']; ?></h3>
        </a>
        <div class="mr5 text-sm gray-600 lowercase">
          <?= $draft['post_date']; ?> |
          <a href="<?= getUrlByName('post.edit', ['id' => $draft['post_id']]); ?>"><?= Translate::get('edit'); ?></a>
        </div>

      <?php } ?>
    <?php } else { ?>
      <?= no_content(Translate::get('there no drafts'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('under development'), 'uid' => $uid]); ?>