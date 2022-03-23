<main>
  <div class="box-flex-white">
    <ul class="nav">

      <?= Html::nav(
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
    <div class="box-white">
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
    <?= Tpl::import('/_block/no-content', ['type' => 'max', 'text' => Translate::get('no.content'), 'icon' => 'bi-journal-richtext']); ?>
  <?php } ?>

</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('being.developed'); ?>
  </div>
</aside>