<footer class="box-shadow-top bg-lightgray">
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= __('web.home.title'); ?></span>
</footer>

<script src="/assets/js/zoom/medium-zoom.min.js"></script>

<script nonce="<?= $_SERVER['nonce']; ?>">
  document.addEventListener('DOMContentLoaded', () => {
    mediumZoom(document.querySelectorAll('img.preview'))
  });
</script>

<?= Tpl::insert('/scripts', ['uid' => $user['id'], 'scroll' => $user['scroll']]); ?>