</container>
<footer class="w-100 mt15 p15 bg-zinc-800 clear">
  <div class="text-sm lowercase">
    <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <?= Translate::get('admin'); ?>
  </div>
</footer>

<?= Tpl::insert('scripts', ['uid' => 1]); ?>