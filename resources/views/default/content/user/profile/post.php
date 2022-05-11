<div class="w-100">
  <?= Tpl::insert('/content/user/profile/header', ['data' => $data]); ?>
  <div class="flex gap">
    <main class="col-two">
      <div class="box-flex">
        <p class="m0"><?= __('app.posts'); ?> <b><?= $data['profile']['login']; ?></b></p>
      </div>
      <?= Tpl::insert('/content/post/post', ['data' => $data]); ?>

      <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/posts'); ?>
    </main>
    <aside>
      <?= Tpl::insert('/content/user/profile/sidebar', ['data' => $data]); ?>
    </aside>
  </div>
</div>