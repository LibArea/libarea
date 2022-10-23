<?php

use Hleb\Constructor\Handlers\Request; ?>

<?php Request::getHead()->addStyles('/assets/css/style.css?21'); ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="item<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?>">
  <header>
    <div class="page-search gap mb-p10">
      <a class="item-logo mb-none" href="<?= url('web'); ?>">
        <?= __('web.catalog'); ?>
      </a>
      <div class="w-100">
        <div data-template="one" id="fing">
          <a class="flex left items-center gap-min gray mb5" href="/">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#home"></use>
            </svg>
            <?= __('web.on_website'); ?>
          </a>
          <div class="flex right items-center gap-max">
            <a id="toggledark" class="header-menu-item gray-600 mb-none">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
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
                <div class="relative gray-600 none mb-block">
                  <div class="trigger">
                    <?= __('web.menu'); ?>
                  </div>
                  <ul class="dropdown">
                    <?= insert('/_block/navigation/item/menu', ['data' => $data]); ?>
                  </ul>
                </div>
              <?php endif; ?>
              <div class="relative">
                <div class="trigger mr5">
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