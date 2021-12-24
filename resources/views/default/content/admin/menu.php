<main class="col-span-10 mb-col-12">
  <div class="bg-white flex flex-row items-center justify-between br-box-gray p15 mb15 size-15">
    <p class="m0">
      <?php if ($type != 'admin') { ?>
        <a href="<?= getUrlByName('admin'); ?>"><?= Translate::get('admin'); ?></a> /
      <?php } ?>
      <span class="red"><?= Translate::get($type); ?></span>
    </p>
    <ul class="flex flex-row list-none m0 p0 center">
      <?php foreach ($pages as $menu) { ?>
        <?php if ($menu['id'] == $sheet) { ?>
          <li class="blue ml30 mb-mr-5 mb-ml-10">
            <i class="<?= $menu['icon']; ?> mr5"></i>
            <span class="mb-size-13"><?= $menu['name']; ?></span>
          </li>
        <?php  } else { ?>
          <li class="ml30 mb-mr-5 mb-ml-10">
            <a class="gray" href="<?= $menu['url']; ?>">
              <i class="<?= $menu['icon']; ?> mr5"></i>
              <span class="mb-size-13"><?= $menu['name']; ?></span></a>
          </li>
        <?php  } ?>
      <?php } ?>
    </ul>
  </div>