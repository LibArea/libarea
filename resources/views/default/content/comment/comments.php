<main>
  <div class="box-flex justify-betweene">
    <ul class="nav">
      <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.comments')]); ?>
    </ul>
    <div class="trigger">
      <i class="bi-info-square gray-600"></i>
    </div>
    <div class="dropdown tooltip"><?= __($data['sheet'] . '.info'); ?></div>
  </div>

  <?php if (!empty($data['comments'])) : ?>
    <div class="box">
      <?= Tpl::insert('/content/comment/comment', ['answer' => $data['comments'], 'user' => $user]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>
  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.comments'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('comments.desc'); ?>
  </div>
</aside>
<?= Tpl::insert('/_block/js-msg-flag', ['uid' => $user['id']]); ?>