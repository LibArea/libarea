<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="menu__left">
  <ul class="menu">
    <?= tabs_nav(
      'menu',
      $data['type'],
      1,
      $pages = Config::get('admin/menu.admin')
    ); ?>
  </ul>
</div>

<main class="col-two">
  <?php if ($data['type'] != 'admin') { ?>
    <div class="box-flex-white">

      <?php $breadcrumbs = new Breadcrumbs();
      $breadcrumbs->base(getUrlByName('admin'), Translate::get('admin')); ?>
      <?php if (!empty($data['facets']) || $data['sheet'] == 'ban.facet') { ?>
        <?php $breadcrumbs->addCrumb(Translate::get('facets'), getUrlByName('admin.facets.all'));
        $breadcrumbs->addCrumb(Translate::get($data['type']), $data['type']); ?>
      <?php } else { ?>
        <?php $breadcrumbs->addCrumb(Translate::get($data['type']), $data['type']); ?>
      <?php } ?>

      <?= $breadcrumbs->render('breadcrumbs'); ?>

      <?php if (!empty($data['users_count'])) { ?><?= $data['users_count'] ?><?php } ?>
      <ul class="flex flex-row list-none m0 p0 center">
        <?php foreach ($menus as $menu) { ?>
          <a class="ml30 mb-mr5 mb-ml10 gray<?php if ($menu['id'] == $data['sheet']) { ?> sky<?php } ?>" href="<?= $menu['url']; ?>" <?php if ($menu['id'] == $data['sheet']) { ?> aria-current="page" <?php } ?>>
            <i class="<?= $menu['icon']; ?> mr5"></i>
            <span><?= $menu['name']; ?></span>
          </a>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>