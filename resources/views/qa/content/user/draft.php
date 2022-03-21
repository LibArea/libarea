<main class="col-two">
  <div class="box-white bg-violet-50">
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
            'icon'  => 'bi-journal-richtext'
          ], [
            'id'    => 'favorites',
            'url'   => getUrlByName('favorites'),
            'title' => Translate::get('favorites'),
            'icon'  => 'bi-bookmark'
          ], [
            'id'    => 'subscribed',
            'url'   => getUrlByName('subscribed'),
            'title' => Translate::get('subscribed'),
            'icon'  => 'bi-bookmark-plus'
          ],
        ]
      ); ?>

    </ul>
  </div>
    <?php if (!empty($data['drafts'])) { ?>
      <div class="box-white bg-violet-50">
      <?php foreach ($data['drafts'] as $draft) { ?>

        <a href="<?= getUrlByName('post', ['id' => $draft['post_id'], 'slug' => $draft['post_slug']]); ?>">
          <h3 class="m0 text-2xl"><?= $draft['post_title']; ?></h3>
        </a>
        <div class="mr5 text-sm gray-600 lowercase">
          <?= $draft['post_date']; ?> |
          <a href="<?= getUrlByName('post.edit', ['id' => $draft['post_id']]); ?>"><?= Translate::get('edit'); ?></a>
        </div>

      <?php } ?>
    </div>
    <?php } else { ?>
    <div class="m10 p15 center gray-600">
      <i class="bi-journal-richtext block text-8xl"></i>
      <?= Translate::get('no.content'); ?>
    </div>
    <?php } ?>
</main>
<aside>
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('being.developed'); ?>
  </div>
</aside>