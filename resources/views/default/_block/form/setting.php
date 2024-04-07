<?= Img::avatar($data['user']['avatar'], $data['user']['login'], 'mr5 ml5 ava', 'small'); ?>
<span class="mr5 ml5">
  <a title="<?= __('app.profile'); ?>" href="<?= url('profile', ['login' => $container->user()->login()]); ?>">
    <?= $data['user']['login']; ?>
  </a>
</span>

<fieldset>
  <span class="name gray">E-mail:</span>
  <span class="mr5 ml5 gray"><?= $data['user']['email']; ?></span>
  <span class="gray-600 text-sm lowercase" data-a11y-dialog-show="my-email"><?= __('app.edit'); ?></span>
</fieldset>

<?php if ($data['new_email']) : ?>
  <code><?= $data['new_email']; ?></code>
  <span class="red text-sm"><?= __('app.not_confirmed'); ?></span>
  <span class="gray-600 text-sm"><?= __('app.resend_email'); ?></span>
<?php endif; ?>

<fieldset>
  <label for="name"><?= __('app.name'); ?></label>
  <input maxlength="11" value="<?= $data['user']['name']; ?>" type="text" name="name">
  <div class="help">0 - 11 <?= __('app.characters'); ?></div>
</fieldset>

<?php insert('/_block/form/textarea', [
  'title'     => __('app.about_me'),
  'type'      => 'text',
  'name'      => 'about',
  'content'   => $data['user']['about'],
  'min'       => 0,
  'max'       => 255,
  'help'      => '0 - 255 ' . __('app.characters') . ' (Markdown)',
]); ?>

<h3 class="m0 mt15"><?= __('app.contacts'); ?></h3>
<div class="gray-600 text-sm"><?= __('app.public_data'); ?></div>
<?php foreach (config('setting', 'contacts') as $block) : ?>
  <fieldset>
    <label for="post_title"><?= __($block['lang']); ?></label>
    <input maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">
    <?php if ($block['help']) : ?>
      <div class="help"><?= $block['help']; ?></div>
    <?php endif; ?>
  </fieldset>
<?php endforeach; ?>

<h3 class="m0 mt15"><?= __('app.other'); ?></h3>
<fieldset id="box">
  <label for="post_content"><?= __('app.color'); ?></label>
  <input type="color" value="<?= $data['user']['color']; ?>" id="colorPicker">
  <input type="hidden" name="color" value="<?= $data['user']['color']; ?>" id="color">
</fieldset>

<fieldset>
  <label for="template"><?= __('app.template'); ?></label>
  <select name="template">
    <?php foreach (config('general', 'templates') as $key => $name) : ?>
      <option <?php if ($data['user']['template'] == $key) { ?>selected<?php } ?> value="<?= $key; ?>">
        <?= $key == config('general', 'template') ? $name . ' ' . __('app.default') : $name; ?>
      </option>
    <?php endforeach; ?>
  </select>
</fieldset>

<fieldset>
  <label for="post_content"><?= __('app.language'); ?></label>
  <select name="lang">
    <?php foreach (config('general', 'languages') as $key => $lang) : ?>
      <option <?php if ($data['user']['lang'] == $key) : ?>selected<?php endif; ?> value="<?= $key; ?>">
        <?= $lang; ?>
      </option>
    <?php endforeach; ?>
  </select>
</fieldset>

<h3 class="m0 mt15"><?= __('app.feed'); ?></h3>
<fieldset>
  <input type="checkbox" name="scroll" <?php if ($data['user']['scroll'] == 1) : ?>checked <?php endif; ?>> <?= __('app.endless_scroll'); ?>
</fieldset>

<?php if (config('feed', 'nsfw')) : ?>
  <fieldset>
    <input type="checkbox" name="nsfw" <?php if ($data['user']['nsfw'] == 1) : ?>checked <?php endif; ?>> <?= __('app.is_nsfw'); ?>
  </fieldset>
<?php endif; ?>


<h3 class="m0 mt15"><?= __('app.post_appearance'); ?></h3>
<fieldset>
  <input type="checkbox" name="post_design" <?php if ($data['user']['post_design'] == 1) : ?>checked <?php endif; ?>> <?= __('app.post_design_card'); ?>
</fieldset>

<div class="flex flex-row items-center justify-between mt20">
  <?= Html::sumbit(__('app.edit')); ?>
  <?php if (config('general', 'deleting_profile')) : ?>
    <a href="setting/deletion" class="gray-600"><?= __('app.delete_profile'); ?></a>
  <?php endif; ?>  
</div>