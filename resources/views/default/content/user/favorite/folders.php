<main>
  <?= insert('/content/user/favorite/nav', ['data' => $data]); ?>
  <div class="box">
    <h3 class="uppercase-box"><?= __('app.folders_s'); ?>: <?= $data['count']; ?></h3>

    <?= insert('/_block/form/select/folders', ['data' => $data]); ?>
  </div>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('help.favorite_info'); ?>...
  </div>
</aside>