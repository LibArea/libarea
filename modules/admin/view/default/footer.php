</div>
<footer class="box-shadow-top mt20">
  <div class="text-sm lowercase">
    <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <?= __('admin'); ?>
  </div>
</footer>
<script src="/assets/js/admin.js"></script>
<?= Tpl::insert('/scripts', ['uid' => 1, 'scroll' => false]); ?>