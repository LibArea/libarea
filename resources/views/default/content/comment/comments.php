<main>
  <div class="box-flex justify-between">
    <ul class="nav">
      <?= Tpl::insert('/_block/navigation/nav', ['list' => config('navigation/nav.comments')]); ?>
    </ul>
    <div class="trigger">
      <i class="bi-info-square gray-600"></i>
    </div>
    <div class="dropdown tooltip"><?= __('meta.' . $data['sheet'] . '.info'); ?></div>
  </div>

  <?php if (!empty($data['comments'])) : ?>
    <div class="box">
      <?= Tpl::insert('/content/comment/comment', ['answer' => $data['comments']]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('meta.comments_desc'); ?>
  </div>
</aside>
<?= Tpl::insert('/_block/js-msg-flag'); ?>