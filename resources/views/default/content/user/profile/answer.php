<div class="w-100">
  <?= insert('/content/user/profile/header', ['data' => $data]); ?>
  <div class="flex gap">
    <main>
      <div class="box-flex">
        <?= __('app.answers'); ?> <b><?= $data['profile']['login']; ?></b>
      </div>
      <?php if (!empty($data['answers'])) : ?>
        <div class="box">
          <?= insert('/content/answer/answer', ['data' => $data]); ?>
        </div>
        <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/answers'); ?>
      <?php else : ?>
        <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_answers'), 'icon' => 'bi-info-lg']); ?>
      <?php endif; ?>
    </main>
    <aside>
      <?= insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>
  </div>
</div>
<?= insert('/_block/js-msg-flag'); ?>
<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(document.querySelectorAll('.content-body p img:not(.emoji)'));
  });
</script>