<div class="box-flex-white">
  <ul class="nav">
    <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.favorites')]); ?>
  </ul>

  <div class="text-sm">
    <i class="bi-plus-lg gray-600 mr5"></i>
    <a href="<?= getUrlByName('favorites.folders'); ?>"><?= Translate::get('folders'); ?></a>
  </div>
</div>