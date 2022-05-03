<main class="col-two">
  <div class="bg-violet">
    <?= Tpl::insert('/content/user/favorite/nav', ['data' => $data]); ?>
  </div>
  <div class="box">
    <h3 class="uppercase-box"><?= __('app.folders_s'); ?>: <?= $data['count']; ?></h3>
    
    <?= Tpl::insert('/_block/form/select/folders', ['data' => $data]); ?>
   
  </div>
</main>
<aside>
  <div class="box bg-violet text-sm sticky top-sm">
    <?= __('app.favorite_info'); ?>...
  </div>
</aside>
