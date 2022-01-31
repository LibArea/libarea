<footer class="box-shadow-top">
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= Translate::get('site.directory'); ?></span>
</footer>

<?= Tpl::insert('scripts', ['uid' => $user['id']]); ?>