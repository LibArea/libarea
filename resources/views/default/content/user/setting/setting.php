<div class="sticky col-span-2 justify-between no-mob">
  <?= includeTemplate('/_block/menu/left', ['sheet' => $data['sheet'], 'uid' => $uid]); ?>
</div>
<main class="col-span-7 mb-col-12">

  <div class="bg-white flex flex-row center items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 no-mob"><?= Translate::get($data['sheet']); ?></p>
    <?= includeTemplate('/_block/setting-nav', ['data' => $data, 'uid' => $uid]); ?>
  </div>

  <div class="bg-white br-box-gray pt15 pr15 pb5 pl15">
    <form class="max-w640" action="<?= getUrlByName('setting.edit'); ?>" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <div class="mb20">
        <?= user_avatar_img($data['user']['user_avatar'], 'small', $data['user']['user_login'], 'mr5 ml5 ava'); ?>
        <span class="mr5 ml5">
          <a title="<?= Translate::get('profile'); ?>" href="<?= getUrlByName('user', ['login' => $uid['user_login']]); ?>">
            <?= $data['user']['user_login']; ?>
          </a>
        </span>
      </div>

      <div class="mb20">
        <span class="name gray">E-mail:</span>
        <span class="mr5 ml5"><?= $data['user']['user_email']; ?></span>
      </div>

      <?= includeTemplate(
        '/_block/form/field-input',
        [
          'data' => [
            [
              'title' => Translate::get('name'),
              'type' => 'text',
              'name' => 'name',
              'value' => $data['user']['user_name'],
              'min' => 3,
              'max' => 11,
              'help' => '3 - 11 ' . Translate::get('characters'),
              'red' => 'red'
            ],
          ]
        ]
      ); ?>

      <?php includeTemplate('/_block/editor/textarea', [
        'title' => Translate::get('about me'),
        'type' => 'text',
        'name' => 'about',
        'content' => $data['user']['user_about'],
        'min' => 0,
        'max' => 255,
        'help' => '0 - 255 ' . Translate::get('characters')
      ]); ?>

      <div id="box" class="mb20">
        <label class="block" for="post_content"><?= Translate::get('color'); ?></label>
        <input type="color" value="<?= $data['user']['user_color']; ?>" id="colorPicker">
        <input type="hidden" name="color" value="<?= $data['user']['user_color']; ?>" id="color">
      </div>

      <div class="mb20 max-w640">
        <label class="block mb5" for="template">
          <?= Translate::get('template'); ?>
        </label>
        <select class="w-100 h30" name="user_template">
          <?php foreach (Config::get('general.templates') as $tpl) { ?> 
            <option <?php if ($data['user']['user_template'] == $tpl) { ?>selected<?php } ?> value="<?= $tpl; ?>">
              <?= $tpl; ?>
            </option>
          <?php } ?>
        </select>
      </div>
         
      <div class="mb20 max-w640">
        <label class="block mb5" for="post_content"><?= Translate::get('language'); ?></label>
        <select class="w-100 h30" name="user_lang">
          <?php foreach (Config::get('general.languages') as $lang) {  ?>
            <option <?php if ($data['user']['user_lang'] == $lang) { ?>selected<?php } ?> value="<?= $lang; ?>">
              <?= Translate::get($lang . '-language'); ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <h3><?= Translate::get('contacts'); ?></h3>
      <?php foreach (Config::get('fields-profile') as $block) { ?>
        <div class="mb20">
          <label class="block mb5" for="post_title"><?= $block['lang']; ?></label>
          <input class="w-100 h30 pl5" maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
          <?php if ($block['help']) { ?>
            <div class="size-14 gray-light-2"><?= $block['help']; ?></div>
          <?php } ?>
        </div>
      <?php } ?>

      <div class="mb20">
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= sumbit(Translate::get('edit')); ?>
      </div>
    </form>
  </div>
</main>
<?= includeTemplate('/_block/sidebar/lang', ['lang' => Translate::get('info-setting')]); ?>