</div>
<footer class="footer shadow-top mt20">
  <div class="text-sm lowercase wrap">
    <?= __('admin.home'); ?> &copy; <?= date('Y'); ?>
  </div>
</footer>

<script src="/assets/js/common.js?<?= config('general', 'version'); ?>"></script>
<script src="/assets/js/app.js?<?= config('general', 'version'); ?>"></script>
<script src="/assets/js/admin.js?<?= config('general', 'version'); ?>"></script>

<script nonce="<?= config('main', 'nonce'); ?>">
  <?= Msg::get(); ?>
</script>