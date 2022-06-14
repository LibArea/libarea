<?php

use Hleb\Constructor\Handlers\Request; ?>

<?php Request::getHead()->addStyles('/assets/css/style.css?014'); ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="item<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?>">
  <header>
    <div class="page-search mb-p10">
      <a class="item-logo" href="<?= url('web'); ?>">
        <?= __('web.catalog'); ?>
      </a>
      <div class="page-search-right mb-ml0">
        <div data-template="one" id="find tippy">
          <a class="tabs black mr15" href="/">
            <i class="bi-house"></i>
            <?= __('web.on_website'); ?>
          </a>
          <div class="flex right items-center gap-max">
            <a id="toggledark" class="header-menu-item gray-600 mb-none">
              <i class="bi-brightness-high text-xl"></i>
            </a>
            <?php if (!UserData::checkActiveUser()) : ?>
              <?php if (config('general.invite') == false) : ?>
                <a class="register gray-600 block" href="<?= url('register'); ?>">
                  <?= __('web.registration'); ?>
                </a>
              <?php endif; ?>
              <a class="gray-600" href="<?= url('login'); ?>">
                <?= __('web.sign_in'); ?>
              </a>
            <?php else : ?>
              <?php if (UserData::checkAdmin()) : ?>
                <div class="relative gray-600">
                  <div class="trigger">
                    <?= __('web.menu'); ?>
                  </div>
                  <ul class="dropdown">
                    <?= insert('/_block/navigation/menu', ['type' => 'admin', 'list' => config('catalog/menu.user')]); ?>
                  </ul>
                </div>
              <?php endif; ?>
              <a class="green" href="<?= url('web.bookmarks'); ?>">
                <?= __('web.favorites'); ?>
              </a>
              <div class="relative">
                <div class="trigger">
                  <?= UserData::getUserLogin(); ?>
                </div>
                <ul class="dropdown user">
                  <?= insert('/_block/navigation/menu-user', ['type' => 'dir', 'list' => config('navigation/menu.user')]); ?>
                </ul>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <form method="get" action="<?= url('search.go'); ?>">
          <input type="text" name="q" placeholder="<?= __('web.find'); ?>" class="page-search__input">
          <input name="cat" value="website" type="hidden">
          <?= csrf_field() ?>
        </form>
      </div>
    </div>
  </header>