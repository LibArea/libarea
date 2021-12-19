<div class="sticky col-span-2 justify-between no-mob">
  <?= import('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd5 br-box-gray p20">
    <h1 class="mt0 mb15 size-21"><?= Translate::get('users'); ?></h1>
    <div class="max-width mr-auto w-100 grid grid-cols-6 gap-2 justify-between">
      <?php foreach ($data['users'] as $ind => $user) { ?>
        <div class="center inline pr10 pl10 mb20 mb-col-2">
          <a href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>">
            <?= user_avatar_img($user['user_avatar'], 'max', $user['user_login'], 'br-rd-50 w64'); ?>
          </a>
          <a class="block black mt5" href="<?= getUrlByName('user', ['login' => $user['user_login']]); ?>">
            <?= $user['user_login']; ?>
          </a>
          <?php if ($user['user_name']) { ?>
            <span class="gray size-14"><?= $user['user_name']; ?></span>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
  <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/users'); ?>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('info-users')]); ?>