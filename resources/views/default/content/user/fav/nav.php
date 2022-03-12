<div class="box-flex-white">
  <ul class="nav">

    <?= tabs_nav(
      'nav',
      $data['sheet'],
      $user,
      $pages = Config::get('menu.favorites'),
    ); ?>

  </ul>

  <div class="text-sm">
    <i class="bi bi-plus-lg gray-400 mr5"></i>
    <a href="<?= getUrlByName('favorites.category'); ?>"><?= Translate::get('categories'); ?></a>
  </div>
</div>