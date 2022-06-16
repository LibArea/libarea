<footer class="box-shadow-top bg-lightgray">
  <?= config('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= __('web.main_title'); ?></span>
</footer>

<script src="/assets/js/medium-zoom.js"></script>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(document.querySelectorAll('img.preview'))
  });
</script>

<?= insert('/scripts'); ?>