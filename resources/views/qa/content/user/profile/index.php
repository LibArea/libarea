<?= import('/content/user/profile/header', ['uid' => $uid, 'data' => $data]); ?>

<?= import('/content/user/profile/sidebar', ['uid' => $uid, 'data' => $data]); ?>

<main class="col-span-8 mb-col-12">
  <?= import('/_block/post', ['data' => $data, 'uid' => $uid]); ?>

  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('profile', ['login' => $data['user']['user_login']]) . '/posts'); ?>
</main>

</div>
<?= import('/_block/wide-footer', ['uid' => $uid]); ?>