<?= Img::avatar($data['user']['avatar'], $data['user']['login'], 'mr5 ml5 ava', 'small'); ?>
<span class="mr5 ml5">
  <a title="<?= __('app.profile'); ?>" href="<?= url('profile', ['login' => UserData::getUserLogin()]); ?>">
    <?= $data['user']['login']; ?>
  </a>
</span>

<fieldset>
  <span class="name gray">E-mail:</span>
  <span class="mr5 ml5"><?= $data['user']['email']; ?></span>
</fieldset>

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
<?php foreach (config('user/setting') as $block) : ?>
  <fieldset>
    <label for="post_title"><?= $block['lang']; ?></label>
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
    <?php foreach (config('general.templates') as $tpl) { ?>
      <option <?php if ($data['user']['template'] == $tpl) { ?>selected<?php } ?> value="<?= $tpl; ?>">
        <?= __('app.' . $tpl); ?>
      </option>
    <?php } ?>
  </select>
</fieldset>

<fieldset>
  <label for="post_content"><?= __('app.language'); ?></label>
  <select name="lang">
    <?php foreach (config('general.languages') as $lang) {  ?>
      <option <?php if ($data['user']['lang'] == $lang) { ?>selected<?php } ?> value="<?= $lang; ?>">
        <?= __('app.' . $lang . '_language'); ?>
      </option>
    <?php } ?>
  </select>
</fieldset>

<fieldset>
  <input type="checkbox" name="scroll" <?php if ($data['user']['scroll'] == 1) : ?>checked <?php endif; ?>> <?= __('app.endless_scroll'); ?>
</fieldset>


<fieldset>
  <input type="hidden" name="nickname" id="nickname" value="">
  <?= Html::sumbit(__('app.edit')); ?>
</fieldset>