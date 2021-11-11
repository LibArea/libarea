<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= Translate::get($data['sheet']); ?></p>
    <?= includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class="bg-white br-box-gray pt15 pr15 pb5 pl15 box setting avatar">
    <form method="POST" action="<?= getUrlByName('setting.avatar.edit'); ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'ava'); ?>

      <div class="mb10 square">
        <div class="input-images"></div>
      </div>

      <div class="clear gray mb10 size-15">
        <div class="mb5"><?= Translate::get('recommended size'); ?>: 240x240px (jpg, jpeg, png)</div>
        <?= sumbit(Translate::get('download')); ?>
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

      <div class="box-form-img-cover mb10">
        <div class="input-images-cover mt10"></div>
      </div>

      <div class="clear gray mb10 size-15">
        <div class="mb5"><?= Translate::get('recommended size'); ?>: 1920x240px (jpg, jpeg, png)</div>
        <?= sumbit(Translate::get('download')); ?>
      </div>
    </form>
  </div>  
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => Translate::get('info-avatar')]); ?>