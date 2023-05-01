<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?21');
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="admin<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?>">

  <header class="d-header">
    <div class="wrap">
      <div class="d-header_contents">
        <div class="none mb-block">
          <div class="menu__button mr10">
            <svg class="icons mr5">
              <use xlink:href="/assets/svg/icons.svg#menu"></use>
            </svg>
          </div>
        </div>
        <div class="flex gap-max items-center">
          <div class="flex gap-min justify-between items-center gray-600">
            <a href="<?= url('admin'); ?>">
              <span class="black"><?= __('admin.home'); ?></span>
            </a>
            <div class="gray-600">/</div>
            <a class="gray-600" href="/">
              <?= __('admin.website'); ?>
            </a>
          </div>
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