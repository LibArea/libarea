<div class="box-flex-white">
  <p class="m0 mb-none"><?= Translate::get($data['sheet']); ?></p>
  <ul class="flex flex-row list-none text-sm">
    <?= tabs_nav(
      'nav',
      $data['sheet'],
      1,
      $pages = Config::get('menu.settings'),
    ); ?>
  </ul>
</div>