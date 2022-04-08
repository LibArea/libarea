<main class="col-two">
  <h1 class="ml15"><?= Translate::get('comments'); ?></h1>

  <?php if (!empty($data['comments'])) { ?>
    <div class="box">
      <?= Tpl::insert('/content/comment/comment', ['answer' => $data['comments'], 'user' => $user]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

  <?php } else { ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.comments'), 'icon' => 'bi bi-info-lg']); ?>
  <?php } ?>
</main>
<aside>
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('comments.desc'); ?>
  </div>
</aside>
<?= Tpl::insert('/_block/js-msg-flag', ['uid' => $user['id']]); ?>