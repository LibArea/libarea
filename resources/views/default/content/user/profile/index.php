<div class="w-100">
  <?= Tpl::import('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>
  <div class="flex gap">
     <main class="col-two">
        <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/@' . $data['profile']['login'] . '/posts'); ?>
     </main>
     <aside>
        <?= Tpl::import('/content/user/profile/sidebar', ['user' => $user, 'data' => $data]); ?>
     </aside>
  </div>
</div>