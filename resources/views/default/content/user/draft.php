<main>
  <div class="box-flex">
    <ul class="nav">
      <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'list' => config('navigation/nav.favorites')]); ?>
    </ul>
  </div>

  <?php if (!empty($data['drafts'])) : ?>
    <div class="box">
      <?php foreach ($data['drafts'] as $draft) : ?>
        <a href="<?= url('post', ['id' => $draft['post_id'], 'slug' => $draft['post_slug']]); ?>">
          <h3 class="m0 text-2xl"><?= $draft['post_title']; ?></h3>
        </a>
        <div class="mr5 text-sm gray-600 lowercase">
          <?= $draft['post_date']; ?> |
          <a href="<?= url('post.edit', ['id' => $draft['post_id']]); ?>"><?= __('app.edit'); ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'bi-journal-richtext']); ?>
  <?php endif; ?>

</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('app.being_developed'); ?>
  </div>
</aside>