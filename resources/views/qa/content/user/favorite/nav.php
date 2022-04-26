<div class="box-flex justify-between">
  <ul class="nav">
    <?= Tpl::insert('/_block/navigation/nav', ['type' => $data['sheet'], 'list' => config('navigation/nav.favorites')]); ?>
  </ul>
  <div class="text-sm">
    <i class="bi-plus-lg gray-600 mr5"></i>
    <a href="<?= url('favorites.folders'); ?>"><?= __('folders'); ?></a>
  </div>
</div>