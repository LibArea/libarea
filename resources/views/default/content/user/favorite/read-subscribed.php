<main>
  <?= insert('/content/user/favorite/nav', ['data' => $data]); ?>
  <div class="mt10">
    <?= insert('/content/post/post-classic', ['data' => $data]); ?>
  </div>
</main>
<aside>
  <div class="box sticky top-sm">
    <?= __('help.' . $data['sheet'] . '_info'); ?>
  </div>
</aside>