<div class="sticky col-span-2 justify-between no-mob">
  <?= import('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">

  <div class="bg-white flex flex-row items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= Translate::get($data['sheet']); ?></p>
    <?= import('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class="bg-white br-box-gray pt15 pr15 pb5 pl15 box setting avatar">
    <form method="POST" action="<?= getUrlByName('setting.avatar.edit'); ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="file-upload mb10" id="file-drag">
        <div class="flex">
          <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'w94 mr20 br-box-gray'); ?>
          <img id="file-image" src="/assets/images/1px.jpg" alt="" class="mr20 w94 h94 br-box-gray">
          <div id="start" class="mt15">
            <input id="file-upload" type="file" name="images" accept="image/*" />
            <div id="notimage" class="none">Please select an image</div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <div class="clear gray mb10 size-15">
        <div class="mb5 size-14"><?= Translate::get('recommended size'); ?>: 240x240px (jpg, jpeg, png)</div>
        <?= sumbit(Translate::get('download')); ?>
      </div>

      <div class="file-upload mt20 mb10" id="file-drag">
        <div class="flex">
          <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
            <div class="relative size-15 mr15">
              <img class="block br-box-gray max-w-100" src="<?= cover_url($data['user']['user_cover_art'], 'user'); ?>">
              <a class="right size-14" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>/delete/cover">
                <?= Translate::get('remove'); ?>
              </a>
            </div>
          <?php } else { ?>
            <div class="block br-box-gray max-w-100 size-14 gray p20 mr15">
              <?= Translate::get('no-cover'); ?>...
            </div>
          <?php } ?>
          <div id="start">
            <img id="file-image bi bi-cloud-download" src="/assets/images/1px.jpg" alt="" class="h94">

            <input id="file-upload" type="file" name="cover" accept="image/*" />
            <div id="notimage" class="none">Please select an image</div>
          </div>
        </div>
        <div id="response" class="hidden">
          <div id="messages"></div>
        </div>
      </div>

      <div class="clear gray mb10 size-15">
        <div class="mb5 size-14"><?= Translate::get('recommended size'); ?>: 1920x240px (jpg, jpeg, png)</div>
        <?= sumbit(Translate::get('download')); ?>
      </div>
    </form>
  </div>
</main>
<?= import('/_block/sidebar/lang', ['lang' => Translate::get('info-avatar')]); ?>