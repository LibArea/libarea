<div class="sticky col-span-2 justify-between mt10 no-mob">
  <nav class="justify-between mt0 ml0 pl0 size-14 max-w170">
    <?php foreach (Config::get('menu-admin') as  $menu) { ?>
      <a class="pt5 pr10 pb5 pl10 block gray bg-hover-light" title="<?= $menu['name']; ?>" href="<?= $menu['url']; ?>">
        <i class="<?= $menu['icon']; ?> middle mr5<?= $type == $menu['item'] ? ' blue' : ''; ?>  size-18"></i>
        <span class="<?= $type == $menu['item'] ? 'blue' : ''; ?>">
          <?= $menu['name']; ?>
        </span>
      </a>
    <?php } ?>
    <hr>
    <span class="size-14 gray-light-2">
      <?= Config::get('meta.name'); ?> &copy; <?= date('Y'); ?>
    </span>
  </nav>
</div>
<main class="col-span-10 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray p15 mb15 size-15">
    <p class="m0">
      <?php if ($type != 'admin') { ?>
        <a href="<?= getUrlByName('admin'); ?>"><?= Translate::get('admin'); ?></a> /
      <?php } ?>
      <span class="red"><?= Translate::get($type); ?></span>
    </p>
    <ul class="flex flex-row list-none m0 p0 center">

      <?php
      $arr_add = [
        'id'        => 'add',
        'url'       => getUrlByName($type . '.add'),
        'content'   => Translate::get('add'),
        'icon'      => 'bi bi-plus-lg'
      ];

      $arr_pages = [
        [
          'id'        => $type . '.all',
          'url'       => getUrlByName('admin.' . $type),
          'content'   => Translate::get('all'),
          'icon'      => 'bi bi-record-circle'
        ],
        [
          'id'        => $type . '.ban',
          'url'       => getUrlByName('admin.' . $type . '.ban'),
          'content'   => Translate::get('deleted'),
          'icon'      => 'bi bi-x-circle'
        ],
      ];
      ?>

      <?= tabs_nav(
        $user_id,
        $sheet,
        $add      = $add === true ? $arr_add : [],
        $pages    = $pages === true ? $arr_pages : [],
      ); ?>

    </ul>
  </div>