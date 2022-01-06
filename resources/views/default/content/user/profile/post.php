<?= import('/content/user/profile/header', ['uid' => $uid, 'data' => $data]); ?>

<?= import('/content/user/profile/sidebar', ['uid' => $uid, 'data' => $data]); ?>

<main class="col-span-7 mb-col-12 mb10">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0"><?= Translate::get('posts'); ?> <b><?= $data['user']['user_login']; ?></b></p>
  </div>
  <div class="mt15">
    <?= import('/_block/post', ['data' => $data, 'uid' => $uid]); ?>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName('profile', ['login' => $data['user']['user_login']]) . '/posts'); ?>
</main>

</div>
<?= import('/_block/wide-footer', ['uid' => $uid]); ?>