<main>
  <?= insert('/content/user/favorite/nav', ['data' => $data]); ?>
  <div class="mt10">
    <?= insert('/content/post/post', ['data' => $data]); ?>
  </div>
</main>
<aside>
  <div class="box text-sm sticky top-sm">
    <?= __('help.preferences_info'); ?>
  </div>
</aside>