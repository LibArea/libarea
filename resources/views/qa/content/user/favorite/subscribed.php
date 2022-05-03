<main class="col-two">
  <div class="bg-violet">
    <?= Tpl::insert('/content/user/favorite/nav', ['data' => $data]); ?>
  </div>
  <div class="mt10">
    <?= Tpl::insert('/content/post/post', ['data' => $data]); ?>
  </div>
</main>
<aside>
  <div class="box bg-violet text-sm sticky top-sm">
    <?= __('app.preferences_info'); ?>
  </div>
</aside>