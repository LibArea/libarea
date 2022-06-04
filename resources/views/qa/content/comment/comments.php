<main>
  <h1 class="ml15"><?= __('app.comments'); ?></h1>

  <?php if (!empty($data['comments'])) : ?>
    <div class="box">
      <?= insert('/content/comment/comment', ['answer' => $data['comments']]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/comments'); ?>

  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'bi bi-info-lg']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-violet text-sm">
    <?= __('meta.comments_desc'); ?>
  </div>
</aside>
<?= insert('/_block/js-msg-flag'); ?>