<main>
  <div class="box-flex justify-between">
    <ul class="nav">
      <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'list' => config('navigation/nav.answers')]); ?>
    </ul>
    <div class="trigger">
      <i class="bi-info-square gray-600"></i>
    </div>
    <div class="dropdown tooltip"><?= __('meta.' . $data['sheet'] . '.info'); ?></div>
  </div>

  <?php if (!empty($data['answers'])) : ?>
    <div class="box">
      <?= Tpl::insert('/content/answer/answer', ['data' => $data]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('meta.answers.desc'); ?>
  </div>
</aside>
<?= Tpl::insert('/_block/js-msg-flag'); ?>