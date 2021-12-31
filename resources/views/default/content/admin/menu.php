<div class="col-span-2 justify-between no-mob">
  <nav class="sticky top70">
  <?= tabs_nav(
    'menu',
    $data['type'],
    $uid,
    $pages = Config::get('menu.admin'),
  ); ?>
  </nav>
</div>

<main class="col-span-10 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray p15 mb15">
    <p class="m0">
      <?php if ($data['type'] != 'admin') { ?>
        <a href="<?= getUrlByName('admin'); ?>"><?= Translate::get('admin'); ?></a> /
      <?php } ?>
      <span class="red-500"><?= Translate::get($data['type']); ?></span>
    </p>
    <ul class="flex flex-row list-none m0 p0 center">
      <?php foreach ($menus as $menu) { ?>
        <a class="ml30 mb-mr-5 mb-ml-10 gray<?php if ($menu['id'] == $data['sheet']) { ?> sky-500<?php } ?>" href="<?= $menu['url']; ?>" <?php if ($menu['id'] == $data['sheet']) { ?> aria-current="page" <?php } ?>>
          <i class="<?= $menu['icon']; ?> mr5"></i>
          <span><?= $menu['name']; ?></span>
        </a>
      <?php } ?>
    </ul>
  </div>