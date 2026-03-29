<?php
/*
 * Fields on the page: profile setup (Contacts)
 * Поля на странице: настройка профиля (Контакты)
 */

$contacts = [
  [
    'url'       => 'website',
    'addition'  => false,
    'title'     => 'website',
    'lang'      => 'app.url',
    'help'      => 'https://site.ru',
    'name'      => 'website'
  ],
  [
    'url'       => false,
    'addition'  => false,
    'title'     => 'location',
    'lang'      => 'app.city',
    'help'      => '',
    'name'      => 'location'
  ],
  [
    'url'       => 'public_email',
    'addition'  => 'mailto:',
    'title'     => 'public_email',
    'lang'      => 'app.email',
    'help'      => '**@**.ru',
    'name'      => 'public_email'
  ],
  [
    'url'       => 'github',
    'addition'  => 'https://github.com/',
    'title'     => 'github',
    'lang'      => 'app.github',
    'help'      => 'https://github.com/***',
    'name'      => 'github'
  ],
  [
    'url'       => 'skype',
    'addition'  => 'skype:',
    'title'     => 'skype',
    'lang'      => 'app.skype',
    'help'      => 'skype:<b>***</b>',
    'name'      => 'skype'
  ],
  [
    'url'       => 'telegram',
    'addition'  => 'https://t.me/',
    'title'     => 'telegram',
    'lang'      => 'app.telegram',
    'help'      => 'https://t.me/***',
    'name'      => 'telegram'
  ],
  [
    'url'       => 'vk',
    'addition'  => 'https://vk.com/',
    'title'     => 'vk',
    'lang'      => 'app.vk',
    'help'      => 'https://vk.com/***',
    'name'      => 'vk'
  ],
];
?>

<?php foreach ($contacts as $block) : ?>
  <fieldset>
    <div class="form-label input-label"><label><?= __($block['lang']); ?></label></div>
    <div class="form-element">
      <input maxlength="150" type="text" value="<?= $data['user'][$block['title']]; ?>" name="<?= $block['name']; ?>">

      <?php if ($block['help']) : ?>
        <div class="help"><?= $block['help']; ?></div>
      <?php endif; ?>
    </div>
  </fieldset>
<?php endforeach; ?>