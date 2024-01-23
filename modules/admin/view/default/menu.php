<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="menu__left">
  <ul class="menu">

    <?php foreach (config('navigation/menu.admin') as $key => $item) :
      $css = empty($item['css']) ? false : $item['css'];
      $isActive = $item['id'] == $data['type'] ? 'active' : false;
      $class = ($css || $isActive) ? ' class="' . $isActive . ' ' .  $css . '"'   : ''; ?>

      <li<?= $class; ?>>
        <a href="<?= $item['url']; ?>">
          <?php if (!empty($item['icon'])) : ?><svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#<?= $item['icon']; ?>"></use>
            </svg><?php endif; ?>
          <?= $item['title']; ?>
        </a>
        </li>
      <?php endforeach; ?>

  </ul>
</div>

<main class="indent-body">
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