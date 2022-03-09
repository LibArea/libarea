<div class="menu__left col-span-2">
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