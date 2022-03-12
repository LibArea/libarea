<main class="col-span-7 mb-col-12">
  <?= Tpl::import('/content/user/fav/nav', ['data' => $data, 'user' => $user]); ?>
  <div class="mt10">
    <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  </div>
</main>
<aside class="col-span-3 mb-none">
  <div class="box-white text-sm sticky top-sm">
    <?= Translate::get('info-preferences'); ?>
  </div>
</aside>