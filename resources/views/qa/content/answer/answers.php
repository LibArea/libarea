<main class="col-two">
  <h1 class="ml15"><?= __('answers'); ?></h1>

  <?php if (!empty($data['answers'])) : ?>
    <div class="box">
      <?= Tpl::insert('/content/answer/answer', ['data' => $data, 'user' => $user]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>

  <?php else : ?>
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.comments'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-violet text-sm">
    <?= __('answers.desc'); ?>
  </div>
</aside>
<?= Tpl::insert('/_block/js-msg-flag', ['uid' => $user['id']]); ?>