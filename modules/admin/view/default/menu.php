<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="col-span-2 mb-none">
 
    <ul class="menu">
     <?= tabs_nav(
          'menu',
          $data['type'],
          1,
          $pages = Config::get('menu.admin')
        ); ?>
     </ul>
  
</div>

<main class="col-span-10 mb-col-12 ">
  <?php if ($data['type'] != 'admin') { ?>
    <div class="box-flex-white">
      <?= (new Breadcrumbs())
        ->base(getUrlByName('admin'), Translate::get('admin'))
        ->addCrumb(Translate::get($data['type']), $data['type'])->render('breadcrumbs'); ?>

      <ul class="flex flex-row list-none m0 p0 center">
        <?php foreach ($menus as $menu) { ?>
          <a class="ml30 mb-mr5 mb-ml10 gray<?php if ($menu['id'] == $data['sheet']) { ?> sky-500<?php } ?>" href="<?= $menu['url']; ?>" <?php if ($menu['id'] == $data['sheet']) { ?> aria-current="page" <?php } ?>>
            <i class="<?= $menu['icon']; ?> mr5"></i>
            <span><?= $menu['name']; ?></span>
          </a>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>