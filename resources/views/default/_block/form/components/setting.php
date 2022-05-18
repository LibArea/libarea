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

<?php insert('/_block/form/textarea', [
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

<?= insert('/_block/form/radio', [
  'data' => [
    [
      'title'     => __('app.endless_scroll'),
      'name'      => 'scroll',
      'checked'   => $data['user']['scroll']
    ],
  ]
]); ?>

<?php
$setting = [
  [
    'url'       => 'website',
    'addition'  => false,
    'title'     => 'website',
    'lang'      => __('app.url'),
    'help'      => 'https://site.ru',
    'name'      => 'website'
  ], [
    'url'       => false,
    'addition'  => false,
    'title'     => 'location',
    'lang'      => __('app.city'),
    'help'      => __('app.for_example') . ': Moscow',
    'name'      => 'location'
  ], [
    'url'       => 'public_email',
    'addition'  => 'mailto:',
    'title'     => 'public_email',
    'lang'      => 'Email',
    'help'      => '**@**.ru',
    'name'      => 'public_email'
  ], [
    'url'       => 'skype',
    'addition'  => 'skype:',
    'title'     => 'skype',
    'lang'      => 'Skype',
    'help'      => 'skype:<b>NICK</b>',
    'name'      => 'skype'
  ], [
    'url'       => 'telegram',
    'addition'  => 'tg://resolve?domain=',
    'title'     => 'telegram',
    'lang'      => 'Telegram',
    'help'      => 'tg://resolve?domain=<b>NICK</b>',
    'name'      => 'telegram'
  ], [
    'url'       => 'vk',
    'addition'  => 'https://vk.com/',
    'title'     => 'vk',
    'lang'      => 'Vk',
    'help'      => 'https://vk.com/<b>NICK / id</b>',
    'name'      => 'vk'
  ],
];

?>

<h3 class="mt15 mb15"><?= __('app.contacts'); ?></h3>
<?php foreach ($setting as $block) : ?>
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