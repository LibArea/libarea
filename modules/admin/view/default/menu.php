<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="menu__left">
  <ul class="menu">
    <?= insert('/_block/navigation/menu', ['type' => $data['type'], 'list' => config('admin/menu.admin')]); ?>
  </ul>
</div>

<main>
  <?php if ($data['type'] != 'admin') : ?>
    <div class="mb15">
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
    </div>

    <ul class="nav">
      <?php foreach ($menus as $menu) : ?>
        <li<?= is_current($menu['url']) ? ' class="active"' : ''; ?>>
          <a class="gray" href="<?= $menu['url']; ?>">
            <span><?= $menu['name']; ?></span>
          </a>
          </li>
        <?php endforeach; ?>
    </ul>
    <div class="mb15"></div>
  <?php endif; ?>