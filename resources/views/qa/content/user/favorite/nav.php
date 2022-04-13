<div class="box-flex">
  <ul class="nav">
    <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'user' => $user, 'list' => Config::get('navigation/nav.favorites')]); ?>
  </ul>
  <div class="text-sm">
    <i class="bi-plus-lg gray-600 mr5"></i>
    <a href="<?= getUrlByName('favorites.folders'); ?>"><?= __('folders'); ?></a>
  </div>
</div>