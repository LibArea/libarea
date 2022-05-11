<div>
  <?= Tpl::insert('/content/user/profile/header', ['data' => $data]); ?>
  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex">
        <p class="m0"><?= __('app.answers'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?php if (!empty($data['answers'])) : ?>
        <?= Tpl::insert('/content/answer/answer', ['data' => $data]); ?>
        <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/answers'); ?>
      <?php else : ?>
        <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_answers'), 'icon' => 'bi-info-lg']); ?>
      <?php endif; ?>
    </main>
    <aside>
      <?= Tpl::insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>
  </div>
</div>
<?= Tpl::insert('/_block/js-msg-flag'); ?>