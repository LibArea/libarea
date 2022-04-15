<main class="col-two">
  <div class="bg-violet">
    <?= Tpl::insert('/content/user/favorite/nav', ['data' => $data, 'user' => $user]); ?>
  </div>
  <div class="mt10">
    <?= Tpl::insert('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  </div>
</main>
<aside>
  <div class="box bg-violet text-sm sticky top-sm">
    <?= __('preferences.info'); ?>
  </div>
</aside>