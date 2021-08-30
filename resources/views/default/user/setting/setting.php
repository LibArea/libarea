<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Setting profile')); ?>
      <?php include TEMPLATE_DIR . '/_block/setting-nav.php'; ?>
    </div>
    <div class="white-box pt15 pr15 pb5 pl15 setting">
      <form action="/users/setting/edit" method="post" enctype="multipart/form-data">
        <?php csrf_field(); ?>
        <div class="boxline">
          <span class="name"><?= lang('Nickname'); ?></span>
          <?= user_avatar_img($data['user']['user_avatar'], 'small', $data['user']['user_login'], 'mr5 ml5 ava'); ?>
          <span class="mr5 ml5"><?= $data['user']['user_login']; ?></span>
        </div>
        <div class="boxline">
          <span class="name">E-mail</span>
          <span class="mr5 ml5"><?= $data['user']['user_email']; ?></span>
        </div>

        <div class="boxline">
          <label class="form-label" for="name"><?= lang('Name'); ?><sup class="red">*</sup></label>
          <input type="text" required class="form-input" name="name" value="<?= $data['user']['user_name']; ?>">
        </div>

        <div class="boxline">
          <label class="form-label" for="about"><?= lang('About me'); ?></label>
          <textarea type="text" rows="4" name="about"><?= $data['user']['user_about']; ?></textarea>
          <div class="box_h gray">0 - 255 <?= lang('characters'); ?></div>
        </div>

        <div id="box" class="boxline">
          <label class="form-label" for="post_content"><?= lang('Color'); ?></label>
          <input type="color" value="<?= $data['user']['user_color']; ?>" id="colorPicker">
          <input type="hidden" name="color" value="" id="color">
        </div>

        <h3><?= lang('Contacts'); ?></h3>

        <div class="boxline">
          <label class="form-label" for="name"><?= lang('URL'); ?></label>
          <input type="text" class="form-input" name="website" value="<?= $data['user']['user_website']; ?>">
          <div class="box_h gray">https://site.ru</div>
        </div>

        <div class="boxline">
          <label class="form-label" for="name"><?= lang('City'); ?></label>
          <input type="text" class="form-input" name="location" value="<?= $data['user']['user_location']; ?>">
          <div class="box_h gray">Москва</div>
        </div>

        <div class="boxline">
          <label class="form-label" for="name"><?= lang('E-mail'); ?></label>
          <input type="email" class="form-input" name="public_email" value="<?= $data['user']['user_public_email']; ?>">
          <div class="box_h gray">**@**.ru</div>
        </div>

        <div class="boxline">
          <label class="form-label" for="name"><?= lang('Skype'); ?></label>
          <input type="text" class="form-input" name="skype" value="<?= $data['user']['user_skype']; ?>">
          <div class="box_h gray">skype:<b>NICK</b></div>
        </div>

        <div class="boxline">
          <label class="form-label" for="name"><?= lang('Twitter'); ?></label>
          <input type="text" class="form-input" name="twitter" value="<?= $data['user']['user_twitter']; ?>">
          <div class="box_h gray">https://twitter.com/<b>NICK</b></div>
        </div>

        <div class="boxline">
          <label class="form-label" for="name"><?= lang('Telegram'); ?></label>
          <input type="text" class="form-input" name="telegram" value="<?= $data['user']['user_telegram']; ?>">
          <div class="box_h gray">tg://resolve?domain=<b>NICK</b></div>
        </div>

        <div class="boxline">
          <label class="form-label" for="name"><?= lang('VK'); ?></label>
          <input type="text" class="form-input" name="vk" value="<?= $data['user']['user_vk']; ?>">
          <div class="box_h gray">https://vk.com/<b>NICK / id</b></div>
        </div>

        <div class="boxline">
          <input type="hidden" name="nickname" id="nickname" value="">
          <button type="submit" class="button"><?= lang('Edit'); ?></button>
        </div>
      </form>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info_setting'); ?>
    </div>
  </aside>
</div>