<footer class="box-shadow-top bg-gray-100">
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= Translate::get('web.home.title'); ?></span>
</footer>

<?= Tpl::insert('scripts', ['uid' => $user['id'], 'scroll' => $user['scroll']]); ?>