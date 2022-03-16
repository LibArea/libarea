<main>
  <?= Tpl::import('/content/user/favorite/nav', ['data' => $data, 'user' => $user]); ?>
  <div class="box-white">
    <h3 class="uppercase-box"><?= Translate::get('folders.s'); ?>: <?= $data['count']; ?></h3>

    <?= Tpl::import('/_block/form/select/folders', ['data' => $data]); ?>

  </div>
</main>
<aside>
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('info-favorite'); ?>...
  </div>
</aside>