<main>
  <div class="mb15">
    <ul class="nav">
      <li class="active"><?= __('app.drafts'); ?></li>
    </ul>
  </div>
  <?php if (!empty($data['drafts'])) : ?>
    <?php foreach ($data['drafts'] as $draft) : ?>
      <div class="box bg-lightgray">
        <a href="<?= url('post', ['id' => $draft['post_id'], 'slug' => $draft['post_slug']]); ?>">
          <h3 class="text-2xl"><?= $draft['post_title']; ?></h3>
        </a>
        <div class="mr5 text-sm gray-600 lowercase">
          <?= Html::langDate($draft['post_date']); ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'max', 'text' => __('app.no_content'), 'icon' => 'post']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('app.being_developed'); ?>
  </div>
</aside>