<div class="flex justify-between mb15">
  <ul class="nav">
    <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.favorites')]); ?>
  </ul>

  <div class="text-sm">
    <a class="sky" href="<?= url('favorites.folders'); ?>">
      <i class="bi-plus-lg"></i>
      <?= __('app.folders'); ?>
    </a>
  </div>
</div>