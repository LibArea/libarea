<main class="col-two">
  <div class="bg-violet">
    <?= insert('/content/user/favorite/nav', ['data' => $data]); ?>
  </div>
  <div class="mt10">
    <?= insert('/content/post/post', ['data' => $data]); ?>
  </div>
</main>
<aside>
  <div class="box bg-violet text-sm sticky top-sm">
    <?= __('help.preferences_info'); ?>
  </div>
</aside>