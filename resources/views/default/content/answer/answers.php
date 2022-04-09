<main>
  <div class="box-flex-white relative">
    <ul class="nav">
      <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.answers')]); ?>
    </ul>
    <div class="trigger">
      <i class="bi-info-square gray-600"></i>
    </div>
    <div class="dropdown tooltip"><?= __($data['sheet'] . '.info'); ?></div>
  </div>

  <?php if (!empty($data['answers'])) { ?>
    <div class="box-white">
      <?= Tpl::insert('/content/answer/answer', ['data' => $data, 'user' => $user]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>
  <?php } else { ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.comments'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>
</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?= __('answers.desc'); ?>
  </div>
</aside>
<?= Tpl::insert('/_block/js-msg-flag', ['uid' => $user['id']]); ?>