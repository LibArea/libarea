<div class="w-100">
  <?= Tpl::insert('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>
  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex-white">
        <p class="m0"><?= __('posts'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?= Tpl::insert('/content/post/post', ['data' => $data, 'user' => $user]); ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/@' . $data['profile']['login'] . '/posts'); ?>
    </main>
    <aside>
      <?= Tpl::insert('/content/user/profile/sidebar', ['user' => $user, 'data' => $data]); ?>
    </aside>
  </div>
</div>