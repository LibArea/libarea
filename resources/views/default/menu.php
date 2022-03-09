<?php
  $css = '';
  $arr = ['profile', 'register', 'login', 'blog.user', 'recover', 'info.page', 'post'];
  if (in_array($data['type'], $arr)) {
     $css = 'menu-none';
  }
?>

<div class="menu__left <?= $css; ?> col-span-2">
  <nav class="sticky top-sm">
    <ul class="menu">
      <?= tabs_nav(
        'menu',
        $data['type'],
        $user,
        $pages = Config::get('menu.left'),
      ); ?>
    </ul>
  </nav>
</div>