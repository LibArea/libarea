<?= insertTemplate('header', ['meta' => $meta]); ?>

<div class="nav-sidebar">
  <div class="menu__left">
    <ul class="menu">

      <?php foreach (config('main', 'menu') as $key => $item) :
        $css = empty($item['css']) ? false : $item['css'];
        $isActive = $item['id'] == $data['type'] ? 'active' : false;
        $class = ($css || $isActive) ? ' class="' . $isActive . ' ' .  $css . '"'   : ''; ?>

        <li<?= $class; ?>>
          <a href="<?= url($item['url']); ?>">
            <?php if (!empty($item['icon'])) : ?><svg class="icon">
                <use xlink:href="/assets/svg/icons.svg#<?= $item['icon']; ?>"></use>
              </svg><?php endif; ?>
            <?= __($item['title']); ?>
          </a>
          </li>
        <?php endforeach; ?>

    </ul>
  </div>
</div>

<main class="box">
  <?php if ($data['type'] != 'admin') : ?>
    <div class="mb15">
      <?= breadcrumb([
        [
          'name' => __('admin.home'),
          'link' => url('admin')
        ], [
          'name' => __('admin.' . $data['type']),
          'link' => $data['type']
        ],
      ]);
      ?>
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