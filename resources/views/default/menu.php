<?php
$css = '';
$arr = ['register', 'login', 'recover', 'post'];
if (in_array($data['type'], $arr)) {
  $css = ' none';
}
?>

<nav class="menu__left<?= $css; ?> mb-none">
  <ul class="menu sticky top-sm">
    <?= tabs_nav(
      'menu',
      $data['type'],
      $user,
      $pages = Config::get('menu.left'),
    ); ?>
  </ul>
</nav>
