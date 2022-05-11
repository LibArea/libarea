<div class="w-100">
  <?= Tpl::insert('/content/user/profile/header', ['data' => $data]); ?>
  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex">
        <p class="m0"><?= __('app.comments'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?php if (!empty($data['comments'])) : ?>
        <div class="box">
          <?= Tpl::insert('/content/comment/comment', ['answer' => $data['comments']]); ?>
        </div>
        <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/comments'); ?>
      <?php else : ?>
        <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'bi-info-lg']); ?>
      <?php endif; ?>
    </main>
    <aside>
      <?= Tpl::insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>
  </div>
</div>
<?= Tpl::insert('/_block/js-msg-flag'); ?>