<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb0 pl15">
      <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), lang('Setting profile')); ?>
      <?php includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
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

        <?php field_input(array(
          array('title' => lang('Heading'), 'type' => 'text', 'name' => 'name', 'value' => $data['user']['user_name'], 'min' => 3, 'max' => 11, 'help' => '3 - 11 ' . lang('characters')),
        )); ?>

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

        <?php field_input(array(
          array('title' => lang('URL'), 'type' => 'text', 'name' => 'website', 'value' => $data['user']['user_website'], 'max' => 150, 'help' => 'https://site.ru'),
          array('title' => lang('City'), 'type' => 'text', 'name' => 'location', 'value' => $data['user']['user_location'], 'max' => 150, 'help' => 'Moscow...'),
          array('title' => lang('E-mail'), 'type' => 'text', 'name' => 'public_email', 'value' => $data['user']['user_public_email'], 'max' => 150, 'help' => '**@**.ru'),
          array('title' => lang('Skype'), 'type' => 'text', 'name' => 'skype', 'value' => $data['user']['user_skype'], 'max' => 150, 'help' => 'skype:<b>NICK</b>'),
          array('title' => lang('Twitter'), 'type' => 'text', 'name' => 'twitter', 'value' => $data['user']['user_twitter'], 'max' => 150, 'help' => 'https://twitter.com/<b>NICK</b>'),
          array('title' => lang('Telegram'), 'type' => 'text', 'name' => 'telegram', 'value' => $data['user']['user_telegram'], 'max' => 150, 'help' => 'tg://resolve?domain=<b>NICK</b>'),
          array('title' => lang('VK'), 'type' => 'text', 'name' => 'vk', 'value' => $data['user']['user_vk'], 'max' => 150, 'help' => 'https://vk.com/<b>NICK / id</b>'),
        )); ?>

        <div class="boxline">
          <input type="hidden" name="nickname" id="nickname" value="">
          <button type="submit" class="button"><?= lang('Edit'); ?></button>
        </div>
      </form>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info-setting'); ?>
    </div>
  </aside>
</div>