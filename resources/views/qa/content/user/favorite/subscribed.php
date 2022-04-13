<main class="col-two">
  <?= Tpl::insert('/content/user/favorite/nav', ['data' => $data, 'user' => $user]); ?>
  <div class="mt10">
    <?= Tpl::insert('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  </div>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('preferences.info'); ?>
  </div>
</aside>