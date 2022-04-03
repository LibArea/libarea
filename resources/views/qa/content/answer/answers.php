<main class="col-two">
  <h1 class="ml15"><?= Translate::get('answers'); ?></h1>

  <?php if (!empty($data['answers'])) { ?>
    <div class="box">
      <?= Tpl::import('/content/answer/answer', ['data' => $data, 'user' => $user]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/answers'); ?>

  <?php } else { ?>
    <?= Tpl::import('/_block/no-content', ['type' => 'small', 'text' => Translate::get('no.comments'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>
</main>
<aside>
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('answers.desc'); ?>
  </div>
</aside>
<?= Tpl::import('/_block/js-msg-flag', ['uid' => $user['id']]); ?>