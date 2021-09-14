<div class="wrap">
  <main class="white-box pt15 pr15 pb5 pl15 box setting avatar">
    <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Change avatar')); ?>
    <?= returnBlock('/setting-nav', ['data' => $data, 'uid' => $uid]); ?>

    <form class="pt10" method="POST" action="/users/setting/avatar/edit" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= user_avatar_img($data['user']['user_avatar'], 'max', $data['user']['user_login'], 'ava'); ?>
      <div class="box-form-img">
        <div class="boxline">
          <div class="input-images"></div>
        </div>
      </div>

      <div class="clear gray size-15">
        <p><?= lang('Recommended size'); ?>: 240x240px (jpg, jpeg, png)</p>
        <p><input type="submit" class="button" value="<?= lang('Download'); ?>" /></p>
      </div>

      <?php if ($data['user']['user_cover_art'] != 'cover_art.jpeg') { ?>
        <img class="cover" src="<?= user_cover_url($data['user']['user_cover_art']); ?>">
        <a class="right size-13" href="/u/<?= $uid['user_login']; ?>/delete/cover">
          <?= lang('Remove'); ?>
        </a>
      <?php } else { ?>
        <?= lang('no-cover'); ?>...
      <?php } ?>

      <div class="box-form-img-cover">
        <div class="boxline">
          <div class="input-images-cover"></div>
        </div>
      </div>

      <div class="clear gray size-15">
        <p><?= lang('Recommended size'); ?>: 1920x240px (jpg, jpeg, png)</p>
        <p><input type="submit" class="button" value="<?= lang('Download'); ?>" /></p>
      </div>
    </form>
  </main>
  <?= aside('lang', ['lang' => lang('info-avatar')]); ?>
</div>