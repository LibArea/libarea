<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">
  <?= breadcrumb('/', lang('home'), getUrlByName('user', ['login' => $uid['user_login']]), lang('profile'), lang('settings')); ?>
  <div class="bg-white flex flex-row center items-center justify-between border-box-1 br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= lang($data['sheet']); ?></p>
    <?= includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class="bg-white border-box-1 pt15 pr15 pb5 pl15">

    <form action="/users/setting/edit" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <div class="boxline">
        <?= user_avatar_img($data['user']['user_avatar'], 'small', $data['user']['user_login'], 'mr5 ml5 ava'); ?>
        <span class="mr5 ml5"><?= $data['user']['user_login']; ?></span>
      </div>

      <div class="boxline">
        <span class="name gray">E-mail:</span>
        <span class="mr5 ml5"><?= $data['user']['user_email']; ?></span>
      </div>

      <?= includeTemplate('/_block/form/field-input', ['data' => [
        ['title' => lang('name'), 'type' => 'text', 'name' => 'name', 'value' => $data['user']['user_name'], 'min' => 3, 'max' => 11, 'help' => '3 - 11 ' . lang('characters')],
      ]]); ?>

      <?php includeTemplate('/_block/editor/textarea', ['title' => lang('about me'), 'type' => 'text', 'name' => 'about', 'content' => $data['user']['user_about'], 'min' => 0, 'max' => 255, 'help' => '0 - 255 ' . lang('characters')]); ?>

      <div id="box" class="boxline">
        <label class="block" for="post_content"><?= lang('color'); ?></label>
        <input type="color" value="<?= $data['user']['user_color']; ?>" id="colorPicker">
        <input type="hidden" name="color" value="<?= $data['user']['user_color']; ?>" id="color">
      </div>

      <!--?= includeTemplate('/_block/form/field-radio', ['data' => [
        ['title' => lang('minimal (experimental version)'), 'name' => 'design_is_minimal', 'checked' => $data['user']['user_design_is_minimal']],
      ]]); ?-->

      <h3><?= lang('contacts'); ?></h3>
      <?php foreach (Config::get('fields-profile') as $block) { ?>
        <div class="boxline">
          <label class="block" for="post_title"><?= $block['lang']; ?></label>
          <input class="w-100 h30" maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
          <?php if ($block['help']) { ?>
            <div class="size-14 gray-light-2"><?= $block['help']; ?></div>
          <?php } ?>
        </div>
      <?php } ?>

      <div class="boxline">
        <input type="hidden" name="nickname" id="nickname" value="">
        <button type="submit" class="button br-rd5 white"><?= lang('edit'); ?></button>
      </div>
    </form>
</main>
<?= includeTemplate('/_block/aside-lang', ['lang' => lang('info-setting')]); ?>