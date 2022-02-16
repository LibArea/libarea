<?= includeTemplate('/view/default/header', ['meta' => $meta]); ?>
<div class="col-span-2 mb-none">
  <nav class="sticky top-sm">

    <ul class="metismenu list-none text-sm" id="menu">
      <?php foreach (Modules\Admin\App\Navigation::menu() as $cats) {
        $active = $data['type'] == $cats['name'] ? 'class="sky-500  "' : 'class="gray   block"';
      ?>

        <?php if ($cats['radical']  == 1) { ?>
          <li> <a <?= $active; ?> href="<?= getUrlByName($cats['url']); ?>">
              <i class="<?= $cats['icon']; ?> middle mr10 text-xl"></i>
              <?= Translate::get($cats['name']); ?>
            </a></li>
        <?php } else { ?>

          <?php if ($cats['parent'] == 0) { ?></li>
            <li><?php } ?>

            <a class="dropdown-btn" href="#">
              <span class="right"><i class="bi bi-chevron-down text-xl"></i></span>
              <i class="bi bi-lis3t middle mr10 text-xl"></i>
              <?= Translate::get($cats['name']); ?>
            </a>
            <div class="none">
              <?php if ($cats['childs'] > 0) { ?>
                <ul class="pl20">
                  <?php foreach ($cats['childs'] as $cat) { ?>
                    <a <?= $active; ?> href="<?= getUrlByName($cat['url']); ?>">
                      <i class="bi bi-circle green-600 middle mr5"></i>
                      <?= Translate::get($cat['name']); ?>
                    </a>
                  <?php } ?>
                </ul>
              <?php } ?>
            </div>
          <?php } ?>

        <?php } ?>
    </ul>

  </nav>
</div>

<main class="col-span-10 mb-col-12">
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
  <script nonce="<?= $_SERVER['nonce']; ?>">
    document.addEventListener("DOMContentLoaded", function(event) {
      var dropdown = document.getElementsByClassName("dropdown-btn");
      var i;
      for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
          this.classList.toggle("active");
          var dropdownContent = this.nextElementSibling;
          if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
          } else {
            dropdownContent.style.display = "block";
          }
        });
      }
    });
  </script>