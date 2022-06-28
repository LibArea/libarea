<div class="w-100">
  <?= insert('/content/user/profile/header', ['data' => $data]); ?>
  <div class="flex gap">
    <main>
      <div class="box-flex">
        <?= __('app.comments'); ?> <b><?= $data['profile']['login']; ?></b>
      </div>
      <?php if (!empty($data['comments'])) : ?>
        <div class="box">
          <?= insert('/content/comment/comment', ['answer' => $data['comments']]); ?>
        </div>
        <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/comments'); ?>
      <?php else : ?>
        <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'info']); ?>
      <?php endif; ?>
    </main>
    <aside>
      <?= insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>
  </div>
</div>
<?= insert('/_block/js-msg-flag'); ?>