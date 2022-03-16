<main class="col-two">
  <?= Tpl::import('/content/user/setting/nav', ['data' => $data, 'user'  => $user]); ?>

  <div class="bg-white box">
    <form class="max-w640" action="<?= getUrlByName('setting.edit'); ?>" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <fieldset>
        <?= user_avatar_img($data['user']['avatar'], 'small', $data['user']['login'], 'mr5 ml5 ava'); ?>
        <span class="mr5 ml5">
          <a title="<?= Translate::get('profile'); ?>" href="<?= getUrlByName('profile', ['login' => $user['login']]); ?>">
            <?= $data['user']['login']; ?>
          </a>
        </span>
      </fieldset>

      <fieldset>
        <span class="name gray">E-mail:</span>
        <span class="mr5 ml5"><?= $data['user']['email']; ?></span>
      </fieldset>

      <fieldset>
        <label for="name"><?= Translate::get('name'); ?></label>
        <input maxlength="11" value="<?= $data['user']['name']; ?>" type="text" name="name">
        <div class="help">0 - 11 <?= Translate::get('characters'); ?></div>  
      </fieldset>

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

      <fieldset id="box">
        <label for="post_content"><?= Translate::get('color'); ?></label>
        <input type="color" value="<?= $data['user']['color']; ?>" id="colorPicker">
        <input type="hidden" name="color" value="<?= $data['user']['color']; ?>" id="color">
      </fieldset>

      <fieldset class="max-w640">
        <label for="template">
          <?= Translate::get('template'); ?>
        </label>
        <select name="template">
          <?php foreach (Config::get('general.templates') as $tpl) { ?>
            <option <?php if ($data['user']['template'] == $tpl) { ?>selected<?php } ?> value="<?= $tpl; ?>">
              <?= Translate::get($tpl); ?>
            </option>
          <?php } ?>
        </select>
      </fieldset>

       <fieldset class="max-w640">
        <label class="block mb5" for="post_content"><?= Translate::get('language'); ?></label>
        <select name="lang">
          <?php foreach (Config::get('general.languages') as $lang) {  ?>
            <option <?php if ($data['user']['lang'] == $lang) { ?>selected<?php } ?> value="<?= $lang; ?>">
              <?= Translate::get($lang . '-language'); ?>
            </option>
          <?php } ?>
        </select>
      </fieldset>

      <h3 class="mt15 mb15"><?= Translate::get('contacts'); ?></h3>
      <?php foreach (Config::get('form/user-setting') as $block) { ?>
        <fieldset>
          <label for="post_title"><?= $block['lang']; ?></label>
          <input maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
          <?php if ($block['help']) { ?>
            <div class="help"><?= $block['help']; ?></div>
          <?php } ?>
        </fieldset>
      <?php } ?>

      <fieldset>
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= sumbit(Translate::get('edit')); ?>
      <fieldset>
    </form>
  </div>
</main>
<aside>
  <div class="box-white bg-violet-50 text-sm">
    <?= Translate::get('info-setting'); ?>
  </div>
</aside>