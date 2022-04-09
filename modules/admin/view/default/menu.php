<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="menu__left">
  <ul class="menu">
    <?= Tpl::insert('/_block/navigation/menu', ['type' => $data['type'], 'user' => 1, 'list' => Config::get('admin/menu.admin')]); ?>
  </ul>
</div>

<main class="col-two">
  <?php if ($data['type'] != 'admin') { ?>
    <div class="box-flex-white">

      <?= Tpl::insert('/_block/navigation/breadcrumbs', [
        'list' => [
          [
            'name' => __('admin'),
            'link' => getUrlByName('admin')
          ], [
            'name' => __($data['type']),
            'link' => $data['type']
          ],
        ]
      ]); ?>

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