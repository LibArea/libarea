<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="menu__left">
  <ul class="menu">
    <?= insert('/_block/navigation/menu', ['type' => $data['type'], 'list' => config('admin/menu.admin')]); ?>
  </ul>
</div>

<main class="col-two">
  <?php if ($data['type'] != 'admin') : ?>
    <div class="box-flex justify-between bg-white">

      <?= insert('/_block/navigation/breadcrumbs', [
        'list' => [
          [
            'name' => __('admin.home'),
            'link' => url('admin')
          ], [
            'name' => __('admin.' . $data['type']),
            'link' => $data['type']
          ],
        ]
      ]); ?>

      <?php if (!empty($data['users_count'])) : ?><?= $data['users_count'] ?><?php endif; ?>
      <ul class="flex flex-row list-none m0 p0 center">
        <?php foreach ($menus as $menu) : ?>
          <a class="ml30 mb-mr5 mb-ml10<?= is_current($menu['url']) ? ' active' : ' gray'; ?>" href="<?= $menu['url']; ?>">
            <i class="<?= $menu['icon']; ?> mr5"></i>
            <span><?= $menu['name']; ?></span>
          </a>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>