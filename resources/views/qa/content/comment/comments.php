<main class="col-two">
  <h1 class="ml15"><?= __('app.comments'); ?></h1>

  <?php if (!empty($data['comments'])) : ?>
    <div class="box">
      <?= Tpl::insert('/content/comment/comment', ['answer' => $data['comments']]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'bi bi-info-lg']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-violet text-sm">
    <?= __('meta.comments.desc'); ?>
  </div>
</aside>
<?= Tpl::insert('/_block/js-msg-flag'); ?>