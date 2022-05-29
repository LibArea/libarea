<?php

use Hleb\Constructor\Handlers\Request; ?>

<?php Request::getHead()->addStyles('/assets/css/style.css?12'); ?>

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
          <div class="flex right items-center">
            <div id="toggledark" class="header-menu-item mb-none only-icon mr30 mb-ml10">
              <i class="bi-brightness-high gray-600 text-xl"></i>
            </div>
            <?php if (!UserData::checkActiveUser()) : ?>
              <?php if (config('general.invite') == false) : ?>
                <a class="register gray-600 mr15 mb-ml10 mb-mr5 block" href="<?= url('register'); ?>">
                  <?= __('web.registration'); ?>
                </a>
              <?php endif; ?>
              <a class="gray-600 mr10 ml10" href="<?= url('login'); ?>">
                <?= __('web.sign_in'); ?>
              </a>
            <?php else : ?>
              <?php if (UserData::checkAdmin()) : ?>
                <div class="relative mr30 gray-600">
                  <div class="trigger">
                    <?= __('web.menu'); ?>
                  </div>
                  <ul class="dropdown">
                    <?= insert('/_block/navigation/menu', ['type' => 'admin', 'list' => config('catalog/menu.user')]); ?>
                  </ul>
                </div>
              <?php endif; ?>
              <a class="mr30 green" href="<?= url('web.bookmarks'); ?>">
                <?= __('web.favorites'); ?>
              </a>
              <div class="mr15 m relative">
                <div class="trigger">
                  <?= UserData::getUserLogin(); ?>
                </div>
                <ul class="dropdown">
                  <?= insert('/_block/navigation/menu', ['type' => 'dir', 'list' => config('navigation/menu.user')]); ?>
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