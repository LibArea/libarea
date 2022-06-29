<div class="w-100">
   <?= insert('/content/user/profile/header', ['data' => $data]); ?>
   <div class="flex gap mb-block">
      <main>
         <?= insert('/content/post/post', ['data' => $data]); ?>
         <?= Html::pagination($data['pNum'], $data['pagesCount'], false, '/@' . $data['profile']['login'] . '/posts'); ?>
      </main>
      <aside>
         <?= insert('/content/user/profile/sidebar', ['data' => $data]); ?>
      </aside>
   </div>
</div>