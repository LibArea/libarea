<div>
  <?= insert('/content/user/profile/header', ['data' => $data]); ?>

  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex">
        <p class="m0"><?= __('app.comments'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?php if (!empty($data['comments'])) : ?>
        <div class="ml15">
          <?= insert('/content/comment/comment', ['answer' => $data['comments']]); ?>
          <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/comments'); ?>
        </div>  
      <?php else : ?>
        <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'bi-info-lg']); ?>
      <?php endif; ?>
    </main>
    <aside>
      <?= insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>
  </div>
</div>
<?= insert('/_block/js-msg-flag'); ?>