<main>
  <div class="flex justify-between mb20">
    <ul class="nav">
      <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.answers')]); ?>
    </ul>
  </div>

  <?php if (!empty($data['answers'])) : ?>
    <div class="mb15">
      <?= insert('/content/answer/answer', ['data' => $data]); ?>
    </div>
    <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/answers'); ?>
  <?php else : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'info']); ?>
  <?php endif; ?>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('meta.answers_desc'); ?>
  </div>
</aside>
<?= insert('/_block/js-msg-flag'); ?>
<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(document.querySelectorAll('.content-body p img:not(.emoji)'));
  });
</script>