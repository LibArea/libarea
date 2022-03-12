<main class="col-span-9 mb-col-12">
  <?= Tpl::import('/content/user/fav/nav', ['data' => $data, 'user' => $user]); ?>
  <div class="mt10">
    <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  </div>
</main>
<aside class="col-span-3 mb-none">
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('info-preferences'); ?>
  </div>
</aside>