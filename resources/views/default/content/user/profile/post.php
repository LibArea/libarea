<?= Tpl::import('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>
 
<?= Tpl::import('/content/user/profile/sidebar', ['user' => $user, 'data' => $data]); ?>

<main class="col-span-7 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('posts'); ?> <b><?= $data['profile']['login']; ?></b></p>
  </div>
  <div class="mt15">
    <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/@' . $data['profile']['login'] . '/posts'); ?>
</main>
<?= Tpl::import('/footer'); ?>