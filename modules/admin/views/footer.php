</div>
<footer class="footer shadow-top mt20">
  <div class="text-sm lowercase wrap">
    <?= __('admin.home'); ?> &copy; <?= date('Y'); ?> 
  </div>
</footer>
<script src="/assets/js/admin.js"></script>

<script nonce="<?= config('main', 'nonce'); ?>">
  <?= Msg::get(); ?>
</script>  