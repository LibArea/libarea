<main class="col-two">
  <?= Tpl::insert('/content/user/favorite/nav', ['data' => $data, 'user' => $user]); ?>
  <div class="box">
    <h3 class="uppercase-box"><?= __('folders.s'); ?>: <?= $data['count']; ?></h3>
    
    <?= Tpl::insert('/_block/form/select/folders', ['data' => $data]); ?>
   
  </div>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('favorite.info'); ?>...
  </div>
</aside>
