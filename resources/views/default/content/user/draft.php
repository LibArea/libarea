<main>
  <div class="box">
    <div class="mb15">
      <ul class="nav">
        <li class="active"><?= __('app.drafts'); ?></li>
      </ul>
    </div>
    <?php if (!empty($data['drafts'])) : ?>
      <?php foreach ($data['drafts'] as $draft) : ?>
        <div class="box">
          <a class="text-xl" href="<?= post_slug($draft['post_id'], $draft['post_slug']); ?>">
            <?= $draft['post_title']; ?>
          </a>
          <div class="mr5 text-sm gray-600 lowercase">
            <?= langDate($draft['post_date']); ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'post']); ?>
    <?php endif; ?>
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('help.draft_info'); ?>
  </div>
</aside>