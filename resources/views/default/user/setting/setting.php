<div class="wrap">
  <main class="white-box pt15 pr15 pb5 pl15">
    <?= breadcrumb('/', lang('Home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('Profile'), lang('Setting profile')); ?>
    <?= returnBlock('/setting-nav', ['data' => $data, 'uid' => $uid]); ?>

    <form class="pt10" action="/users/setting/edit" method="post" enctype="multipart/form-data">
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

      <?php field('input', [
        ['title' => lang('Name'), 'type' => 'text', 'name' => 'name', 'value' => $data['user']['user_name'], 'min' => 3, 'max' => 11, 'help' => '3 - 11 ' . lang('characters')],
      ]); ?>

      <?php returnBlock('editor/textarea', ['title' => lang('About me'), 'type' => 'text', 'name' => 'about', 'content' => $data['user']['user_about'], 'min' => 0, 'max' => 255, 'help' => '0 - 255 ' . lang('characters')]); ?>

      <div id="box" class="boxline">
        <label class="form-label" for="post_content"><?= lang('Color'); ?></label>
        <input type="color" value="<?= $data['user']['user_color']; ?>" id="colorPicker">
        <input type="hidden" name="color" value="" id="color">
      </div>

      <h3><?= lang('Contacts'); ?></h3>

      <?php field('input', [
        ['title' => lang('URL'), 'type' => 'text', 'name' => 'website', 'value' => $data['user']['user_website'], 'max' => 150, 'help' => 'https://site.ru'],
        ['title' => lang('City'), 'type' => 'text', 'name' => 'location', 'value' => $data['user']['user_location'], 'max' => 150, 'help' => 'Moscow...'],
        ['title' => lang('E-mail'), 'type' => 'text', 'name' => 'public_email', 'value' => $data['user']['user_public_email'], 'max' => 150, 'help' => '**@**.ru'],
        ['title' => lang('Skype'), 'type' => 'text', 'name' => 'skype', 'value' => $data['user']['user_skype'], 'max' => 150, 'help' => 'skype:<b>NICK</b>'],
        ['title' => lang('Twitter'), 'type' => 'text', 'name' => 'twitter', 'value' => $data['user']['user_twitter'], 'max' => 150, 'help' => 'https://twitter.com/<b>NICK</b>'],
        ['title' => lang('Telegram'), 'type' => 'text', 'name' => 'telegram', 'value' => $data['user']['user_telegram'], 'max' => 150, 'help' => 'tg://resolve?domain=<b>NICK</b>'],
        ['title' => lang('VK'), 'type' => 'text', 'name' => 'vk', 'value' => $data['user']['user_vk'], 'max' => 150, 'help' => 'https://vk.com/<b>NICK / id</b>'],
      ]); ?>

      <div class="boxline">
        <input type="hidden" name="nickname" id="nickname" value="">
        <button type="submit" class="button"><?= lang('Edit'); ?></button>
      </div>
    </form>
  </main>
  <?= returnBlock('aside-lang', ['lang' => lang('info-setting')]); ?>
</div>