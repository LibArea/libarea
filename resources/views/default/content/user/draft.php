<main>
  <div class="box-flex">
    <ul class="nav">
      <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.favorites')]); ?>
    </ul>
  </div>

  <?php if (!empty($data['drafts'])) : ?>
    <div class="box">
      <?php foreach ($data['drafts'] as $draft) : ?>
        <a href="<?= getUrlByName('post', ['id' => $draft['post_id'], 'slug' => $draft['post_slug']]); ?>">
          <h3 class="m0 text-2xl"><?= $draft['post_title']; ?></h3>
        </a>
        <div class="mr5 text-sm gray-600 lowercase">
          <?= $draft['post_date']; ?> |
          <a href="<?= getUrlByName('post.edit', ['id' => $draft['post_id']]); ?>"><?= __('edit'); ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'max', 'text' => __('no.content'), 'icon' => 'bi-journal-richtext']); ?>
  <?php endif; ?>

</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('being.developed'); ?>
  </div>
</aside>