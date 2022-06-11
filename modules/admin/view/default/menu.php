<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="menu__left">
  <ul class="menu">
    <?= insert('/_block/navigation/menu', ['type' => $data['type'], 'list' => config('admin/menu.admin')]); ?>
  </ul>
</div>

<main>
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
      <ul class="flex flex-row list-none gap">
        <?php foreach ($menus as $menu) : ?>
          <a class="<?= is_current($menu['url']) ? ' active' : ' gray'; ?>" href="<?= $menu['url']; ?>">
            <i class="<?= $menu['icon']; ?>"></i>
            <span><?= $menu['name']; ?></span>
          </a>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>