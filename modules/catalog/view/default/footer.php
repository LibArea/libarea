<footer class="box-shadow-top bg-lightgray">
  <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?> — <span class="lowercase"><?= __('web.home.title'); ?></span>
</footer>

<?= Tpl::insert('/scripts', ['uid' => $user['id'], 'scroll' => $user['scroll']]); ?>