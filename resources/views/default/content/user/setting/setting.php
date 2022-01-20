<div class="col-span-2 justify-between mb-none">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $user,
    $pages = Config::get('menu.left'),
  ); ?>
  </nav>
</div>

<main class="col-span-7 mb-col-12">

  <div class="bg-white flex flex-row center items-center justify-between br-box-gray br-rd5 p15 mb15">
    <p class="m0 mb-none"><?= Translate::get($data['sheet']); ?></p>
    <?= Tpl::import('/content/user/setting/nav', ['data' => $data, 'user'  => $user]); ?>
  </div>

  <div class="bg-white br-box-gray pt15 pr15 pb5 pl15">
    <form class="max-w640" action="<?= getUrlByName('setting.edit'); ?>" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <div class="mb20">
        <?= user_avatar_img($data['user']['avatar'], 'small', $data['user']['login'], 'mr5 ml5 ava'); ?>
        <span class="mr5 ml5">
          <a title="<?= Translate::get('profile'); ?>" href="/@<?= $user['login']; ?>">
            <?= $data['user']['login']; ?>
          </a>
        </span>
      </div>

      <div class="mb20">
        <span class="name gray">E-mail:</span>
        <span class="mr5 ml5"><?= $data['user']['email']; ?></span>
      </div>

      <?= Tpl::import(
        '/_block/form/field-input',
        [
          'user'  => $user,
          'data' => [
            [
              'title'   => Translate::get('name'),
              'type'    => 'text',
              'name'    => 'name',
              'value'   => $data['user']['name'],
              'min'     => 3,
              'max'     => 11,
              'help'    => '3 - 11 ' . Translate::get('characters'),
              'red'     => 'red',

            ],
          ]
        ]
      ); ?>

      <?php Tpl::import('/_block/editor/textarea', [
        'title'     => Translate::get('about me'),
        'type'      => 'text',
        'name'      => 'about',
        'content'   => $data['user']['about'],
        'min'       => 0,
        'max'       => 255,
        'help'      => '0 - 255 ' . Translate::get('characters'),
        'user'       => $user
      ]); ?>

      <div id="box" class="mb20">
        <label class="block" for="post_content"><?= Translate::get('color'); ?></label>
        <input type="color" value="<?= $data['user']['color']; ?>" id="colorPicker">
        <input type="hidden" name="color" value="<?= $data['user']['color']; ?>" id="color">
      </div>

      <div class="mb20 max-w640">
        <label class="block mb5" for="template">
          <?= Translate::get('template'); ?>
        </label>
        <select class="w-100 h30" name="template">
          <?php foreach (Config::get('general.templates') as $tpl) { ?>
            <option <?php if ($data['user']['template'] == $tpl) { ?>selected<?php } ?> value="<?= $tpl; ?>">
              <?= Translate::get($tpl); ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <div class="mb20 max-w640">
        <label class="block mb5" for="post_content"><?= Translate::get('language'); ?></label>
        <select class="w-100 h30" name="lang">
          <?php foreach (Config::get('general.languages') as $lang) {  ?>
            <option <?php if ($data['user']['lang'] == $lang) { ?>selected<?php } ?> value="<?= $lang; ?>">
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
            <div class="text-sm gray-400"><?= $block['help']; ?></div>
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
<?= Tpl::import('/_block/sidebar/lang', ['lang' => Translate::get('info-setting')]); ?>