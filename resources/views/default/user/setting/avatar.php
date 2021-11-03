<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">

  <?= breadcrumb(
    '/',
    Translate::get('home'),
    getUrlByName('user', ['login' => $uid['user_login']]),
    Translate::get('profile'),
    Translate::get('change avatar')
  ); ?>

  <div class="bg-white flex flex-row items-center justify-between br-box-grey br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= Translate::get($data['sheet']); ?></p>
    <?= includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class=" bg-white br-box-grey pt15 pr15 pb5 pl15 box setting avatar">
    <form method="POST" action="/users/setting/avatar/edit" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'ava'); ?>
      <div class="box-form-img">
        <div class="mb20">
          <div class="input-images"></div>
        </div>
      </div>

      <div class="clear gray size-15">
        <p><?= Translate::get('recommended size'); ?>: 240x240px (jpg, jpeg, png)</p>
        <p><input type="submit" class="button block br-rd5 white" value="<?= Translate::get('download'); ?>" /></p>
      </div>

      <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
        <div class="relative size-15">
          <img class="block max-w-100" src="<?= user_cover_url($data['user']['user_cover_art']); ?>">
          <a class="right size-14" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>/delete/cover">
            <?= Translate::get('remove'); ?>
          </a>
        </div>
      <?php } else { ?>
        <?= Translate::get('no-cover'); ?>...
      <?php } ?>

      <div class="box-form-img-cover">
        <div class="mb20">
          <div class="input-images-cover"></div>
        </div>
      </div>

      <div class="clear gray size-15">
        <p><?= Translate::get('recommended size'); ?>: 1920x240px (jpg, jpeg, png)</p>
        <p><input type="submit" class="button white" value="<?= Translate::get('download'); ?>" /></p>
      </div>
    </form>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('info-avatar')]); ?>