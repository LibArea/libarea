<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?21');
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="admin<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?><?php if (Request::getCookie('menuYesNo') == 'menuno') : ?> menuno<?php endif; ?>">

  <header class="d-header">
    <div class="wrap">
      <div class="d-header_contents">
        <div class="flex items-center gray-600 gap-min mr20">
          <svg id="togglemenu" class="icons pointer">
            <use xlink:href="/assets/svg/icons.svg#menu"></use>
          </svg>

          <svg class="icons menu__button none">
            <use xlink:href="/assets/svg/icons.svg#menu"></use>
          </svg>

          <a class="logo" href="<?= url('admin'); ?>"><?= __('admin.home'); ?></a>
          <div class="gray-600">/</div>
          <a class="gray-600" href="/">
            <?= __('admin.website'); ?>
          </a>
        </div>
        <div class="flex gap-max items-center w-90 ml20">
          <a class="<?= is_current(url('admin.users')) ? ' active' : ' gray-600'; ?>" href="<?= url('admin.users'); ?>">
            <svg class="icons mr5">
              <use xlink:href="/assets/svg/icons.svg#users"></use>
            </svg>
            <span class="mb-none middle text-sm"><?= __('admin.users'); ?></span>
          </a>
          <a class="<?= is_current(url('admin.facets.all')) ? ' active' : ' gray-600'; ?> mb-none" href="<?= url('admin.facets.all'); ?>">
            <svg class="icons mr5">
              <use xlink:href="/assets/svg/icons.svg#facets"></use>
            </svg>
            <span class="mb-none middle text-sm"><?= __('admin.facets'); ?></span>
          </a>
          <a class="<?= is_current(url('admin.tools')) ? ' active' : ' gray-600'; ?>" href="<?= url('admin.tools'); ?>">
            <svg class="icons mr5">
              <use xlink:href="/assets/svg/icons.svg#tool"></use>
            </svg>
            <span class="mb-none middle text-sm"><?= __('admin.tools'); ?></span>
          </a>
          <!-- Hide so as not to embarrass
          <a class="<?= is_current(url('admin.settings.general')) ? ' active' : ' gray-600'; ?> mb-none" href="<?= url('admin.settings.general'); ?>">
            <svg class="icons mr5">
              <use xlink:href="/assets/svg/icons.svg#settings"></use>
            </svg>
            <span class="mb-none middle text-sm"><?= __('admin.settings'); ?></span>
          </a> -->
        </div>
        <div class="mb-block">
          <span class="mb-none gray-600"><?= Request::getRemoteAddress(); ?></span>
        </div>
      </div>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">