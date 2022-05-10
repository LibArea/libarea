<main>
  <?= Tpl::insert('/content/user/setting/nav', ['data' => $data]); ?>

  <div class="box">
    <form class="max-w780" action="<?= url('setting.edit'); ?>" method="post" enctype="multipart/form-data">
      <?php csrf_field(); ?>

      <?= Html::image($data['user']['avatar'], $data['user']['login'], 'mr5 ml5 ava', 'avatar', 'small'); ?>
      <span class="mr5 ml5">
        <a title="<?= __('app.profile'); ?>" href="<?= url('profile', ['login' => UserData::getUserLogin()]); ?>">
          <?= $data['user']['login']; ?>
        </a>
      </span>

      <fieldset>
        <span class="name gray">E-mail:</span>
        <span class="mr5 ml5"><?= $data['user']['email']; ?></span>
      </fieldset>

      <fieldset class="max-w300">
        <label for="name"><?= __('app.name'); ?></label>
        <input maxlength="11" value="<?= $data['user']['name']; ?>" type="text" name="name">
        <div class="help">0 - 11 <?= __('app.characters'); ?></div>
      </fieldset>

      <?php Tpl::insert('/_block/form/textarea', [
        'title'     => __('app.about_me'),
        'type'      => 'text',
        'name'      => 'about',
        'content'   => $data['user']['about'],
        'min'       => 0,
        'max'       => 255,
        'help'      => '0 - 255 ' . __('app.characters'),
      ]); ?>

      <fieldset id="box" class="max-w300">
        <label for="post_content"><?= __('app.color'); ?></label>
        <input type="color" value="<?= $data['user']['color']; ?>" id="colorPicker">
        <input type="hidden" name="color" value="<?= $data['user']['color']; ?>" id="color">
      </fieldset>

      <fieldset class="max-w300">
        <label for="template"><?= __('app.template'); ?></label>
        <select name="template">
          <?php foreach (config('general.templates') as $tpl) { ?>
            <option <?php if ($data['user']['template'] == $tpl) { ?>selected<?php } ?> value="<?= $tpl; ?>">
              <?= __('app.' . $tpl); ?>
            </option>
          <?php } ?>
        </select>
      </fieldset>

      <fieldset class="max-w300">
        <label for="post_content"><?= __('app.language'); ?></label>
        <select name="lang">
          <?php foreach (config('general.languages') as $lang) {  ?>
            <option <?php if ($data['user']['lang'] == $lang) { ?>selected<?php } ?> value="<?= $lang; ?>">
              <?= __('app.' . $lang . '_language'); ?>
            </option>
          <?php } ?>
        </select>
      </fieldset>

      <?= Tpl::insert('/_block/form/radio', [
        'data' => [
          [
            'title'     => __('app.endless_scroll'),
            'name'      => 'scroll',
            'checked'   => $data['user']['scroll']
          ],
        ]
      ]); ?>

      <h3 class="mt15 mb15"><?= __('app.contacts'); ?></h3>
      <?php foreach (config('form/user-setting') as $block) : ?>
        <fieldset class="max-w300">
          <label for="post_title"><?= $block['lang']; ?></label>
          <input maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
          <?php if ($block['help']) : ?>
            <div class="help"><?= $block['help']; ?></div>
          <?php endif; ?>
        </fieldset>
      <?php endforeach; ?>

      <fieldset>
        <input type="hidden" name="nickname" id="nickname" value="">
        <?= Html::sumbit(__('app.edit')); ?>
      </fieldset>
    </form>
  </div>
</main>
<aside>
  <div class="box text-sm">
    <?= __('help.setting_info'); ?>
  </div>
</aside>