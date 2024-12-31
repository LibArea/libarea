<div class="flex flex-col w-100">
  <?= insert('/content/user/profile/header', ['data' => $data]); ?>
  <div class="flex gap">
    <main class="flex-auto">
      <div class="mb15"><?= __('app.posts'); ?> <b><?= $data['profile']['login']; ?></b></div>

      <?= insert('/content/post/type-post', ['data' => $data]); ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/posts'); ?>
    </main>
    <aside>
      <?= insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>
  </div>
</div>