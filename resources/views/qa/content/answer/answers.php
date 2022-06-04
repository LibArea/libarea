<main>
  <h1 class="ml15"><?= __('app.answers'); ?></h1>

  <?php if (!empty($data['answers'])) : ?>
    <div class="box">
      <?= insert('/content/answer/answer', ['data' => $data]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/answers'); ?>

  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-violet text-sm">
    <?= __('meta.answers_desc'); ?>
  </div>
</aside>
<?= insert('/_block/js-msg-flag'); ?>