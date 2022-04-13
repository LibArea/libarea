<?php
Request::getHead()->addStyles('/assets/css/style.css?12');
?>

<?= Tpl::insert('/meta', ['meta' => $meta]); ?>

<body class="bg-lightgray<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?>">

  <header class="d-header sticky top0">
    <div class="wrap">
      <div class="d-header_contents">
        <div class="none mb-block">
          <div class="menu__button mr10">
            <i class="bi-list gray-600 text-xl"></i>
          </div>
        </div>
        <div class="flex items-center w200">
          <a href="<?= getUrlByName('admin'); ?>">
            <span class="black"><?= __('admin'); ?></span>
          </a>
        </div>
        <div class="relative w-90">
          <a class="gray-600" href="<?= getUrlByName('admin.users'); ?>">
            <i class="bi-people middle mr5"></i>
            <span class="mb-none middle"><?= __('users'); ?></span>
          </a>
          <a class="gray-600 ml30" href="<?= getUrlByName('admin.facets.all'); ?>">
            <i class="bi-columns-gap middle mr5"></i>
            <span class="mb-none middle text-sm"><?= __('facets'); ?></span>
          </a>
          <a class="gray-600 ml30" href="<?= getUrlByName('admin.tools'); ?>">
            <i class="bi-tools middle mr5"></i>
            <span class="mb-none middle text-sm"><?= __('tools'); ?></span>
          </a>
        </div>
        <div class="m15 gray-600 mb-none">
          <?= Request::getRemoteAddress(); ?>
        </div>
        <a class="ml5 sky" href="/">
          <i class="bi-house"></i>
        </a>
      </div>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">