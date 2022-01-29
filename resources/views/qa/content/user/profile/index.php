<?= Tpl::import('/content/user/profile/header', ['user' => $user, 'data' => $data]); ?>
 
<?= Tpl::import('/content/user/profile/sidebar', ['user' => $user, 'data' => $data]); ?>

<main class="col-span-8 mb-col-12">
  <?= Tpl::import('/content/post/post', ['data' => $data, 'user' => $user]); ?>

  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/@' . $data['profile']['login'] . '/posts'); ?>
</main>
<?= Tpl::import('/footer'); ?>