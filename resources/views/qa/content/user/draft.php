<main class="col-span-9 mb-col-12">
  <div class="box-white">
    <ul class="nav">

      <?= tabs_nav(
        'nav',
        $data['sheet'],
        $user,
        $pages = [
          [
            'id'    => 'drafts',
            'url'   => getUrlByName('drafts'),
            'title' => Translate::get('drafts'),
            'icon'  => 'bi bi-bookmark'
          ], [
            'id'    => 'favorites',
            'url'   => getUrlByName('favorites'),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi bi-bookmark'
          ], [
            'id'    => 'subscribed',
            'url'   => getUrlByName('subscribed'),
            'title' => Translate::get('subscribed'),
            'icon'  => 'bi bi-bookmark-plus'
          ],
        ]
      ); ?>

    </ul>
  </div>
  <div class="box-white">
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
      <?= no_content(Translate::get('no.content'), 'bi bi-info-lg'); ?>
    <?php } ?>
  </div>
</main>
<aside class="col-span-3 mb-none">
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('being.developed'); ?>
  </div>
</aside>