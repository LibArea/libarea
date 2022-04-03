<main>
  <div class="box-flex-white relative">
    <ul class="nav">
      <?= Tpl::import('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.comments')]); ?>
    </ul>
    <div class="trigger">
      <i class="bi-info-square gray-600"></i>
    </div>
    <div class="dropdown tooltip"><?= Translate::get($data['sheet'] . '.info'); ?></div>
  </div>

  <?php if (!empty($data['comments'])) { ?>
    <div class="box-white">
      <?= Tpl::import('/content/comment/comment', ['answer' => $data['comments'], 'user' => $user]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>
  <?php } else { ?>
    <?= Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.comments'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>
</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('comments.desc'); ?>
  </div>
</aside>
<?= Tpl::import('/_block/js-msg-flag', ['uid' => $user['id']]); ?>