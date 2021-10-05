<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">

  <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('profile'), lang('change avatar')); ?>
  <div class="bg-white flex flex-row items-center justify-between border-box-1 br-rd-5 p15 mb15">
    <p class="m0 no-mob"><?= lang($data['sheet']); ?></p>
    <?= includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class=" bg-white border-box-1 pt15 pr15 pb5 pl15 box setting avatar">
    <form method="POST" action="/users/setting/avatar/edit" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'ava'); ?>
      <div class="box-form-img">
        <div class="boxline">
          <div class="input-images"></div>
        </div>
      </div>

      <div class="clear gray size-15">
        <p><?= lang('recommended size'); ?>: 240x240px (jpg, jpeg, png)</p>
        <p><input type="submit" class="button block br-rd-5 white" value="<?= lang('download'); ?>" /></p>
      </div>

      <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
        <div class="relative size-15">
          <img class="block max-w-100" src="<?= user_cover_url($data['user']['user_cover_art']); ?>">
          <a class="right size-14" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>/delete/cover">
            <?= lang('remove'); ?>
          </a>
       </div>   
      <?php } else { ?>
        <?= lang('no-cover'); ?>...
      <?php } ?>

      <div class="box-form-img-cover">
        <div class="boxline">
          <div class="input-images-cover"></div>
        </div>
      </div>

      <div class="clear gray size-15">
        <p><?= lang('recommended size'); ?>: 1920x240px (jpg, jpeg, png)</p>
        <p><input type="submit" class="button white" value="<?= lang('download'); ?>" /></p>
      </div>
    </form>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-avatar')]); ?>