<div class="box-flex justify-between">
  <ul class="nav">
    <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.favorites')]); ?>
  </ul>

  <div class="text-sm">
    <i class="bi-plus-lg gray-600 mr5"></i>
    <a href="<?= url('favorites.folders'); ?>"><?= __('app.folders'); ?></a>
  </div>
</div>