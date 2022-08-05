<main>
  <?= insert('/content/user/favorite/nav', ['data' => $data]); ?>
  <div class="mt10">
    <?= insert('/content/post/post', ['data' => $data]); ?>
  </div>
</main>
<aside>
  <div class="box bg-beige sticky top-sm">
    <?= __('help.' . $data['sheet'] . '_info'); ?>
  </div>
</aside>