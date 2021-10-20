<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <div class="bg-white br-rd5 border-box-1 p20">
    <h1 class="mt0 mb15 size-21"><?= lang('users'); ?></h1>
    <div class="wrap grid grid-cols-6 gap-4 justify-between">
      <?php foreach ($data['users'] as $ind => $user) { ?>
        <div class="center pr10 pl10 mb-col-2">
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
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-users')]); ?>