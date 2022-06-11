<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?04');
?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="admin<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?>">

  <header class="d-header sticky top0">
    <div class="wrap">
      <div class="d-header_contents">
        <div class="none mb-block">
          <div class="menu__button mr10">
            <i class="bi-list gray-600 text-xl"></i>
          </div>
        </div>
        <div class="flex gap-max items-center">
          <a class="w160" href="<?= url('admin'); ?>">
            <span class="black"><?= __('admin.home'); ?></span>
          </a>
          <a class="<?= is_current(url('admin.users')) ? ' active' : ' gray-600'; ?>" href="<?= url('admin.users'); ?>">
            <i class="bi-people middle mr5"></i>
            <span class="mb-none middle"><?= __('admin.users'); ?></span>
          </a>
          <a class="<?= is_current(url('admin.facets.all')) ? ' active' : ' gray-600'; ?>" href="<?= url('admin.facets.all'); ?>">
            <i class="bi-columns-gap middle mr5"></i>
            <span class="mb-none middle text-sm"><?= __('admin.facets'); ?></span>
          </a>
          <a class="<?= is_current(url('admin.tools')) ? ' active' : ' gray-600'; ?>" href="<?= url('admin.tools'); ?>">
            <i class="bi-tools middle mr5"></i>
            <span class="mb-none middle text-sm"><?= __('admin.tools'); ?></span>
          </a>
        </div>
        <div class="gray-600 mb-none">
          <?= Request::getRemoteAddress(); ?>
          <a class="ml5 sky" href="/"><i class="bi-house"></i></a>
        </div>
      </div>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">