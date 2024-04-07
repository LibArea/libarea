<footer>
  <div class="wrap">
    <?= config('meta', 'name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= __('web.main_title'); ?></span>
  </div>
</footer>

<?= insert('/scripts'); ?>