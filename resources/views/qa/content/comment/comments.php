<main class="col-two">
  <h1 class="ml15"><?= __('comments'); ?></h1>

  <?php if (!empty($data['comments'])) : ?>
    <div class="box">
      <?= Tpl::insert('/content/comment/comment', ['answer' => $data['comments'], 'user' => $user]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.comments'), 'icon' => 'bi bi-info-lg']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-violet text-sm">
    <?= __('comments.desc'); ?>
  </div>
</aside>
<?= Tpl::insert('/_block/js-msg-flag', ['uid' => $user['id']]); ?>