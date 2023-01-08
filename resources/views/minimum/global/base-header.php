<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?7');
Request::getHead()->addStyles('/assets/css/minimum.css?7');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="body-minimum<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?><?php if (Request::getCookie('menuYesNo') == 'menuno') : ?> menuno<?php endif; ?>">
  <header class="text-header wrap">
    <div class="d-header_contents justify-between">

      <div class="flex gap-max items-center">
        <a title="<?= __('app.home'); ?>" class="logo" href="/">
          L
        </a>
        <ul class="nav scroll-menu mb-w150">
          <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.home')]); ?>
        </ul>
      </div>

      <?php if (!UserData::checkActiveUser()) : ?>
        <div class="flex gap-max items-center">
          <div id="toggledark" class="header-menu-item mb-none">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#sun"></use>
            </svg>
          </div>
          <?php if (config('general.invite') == false) : ?>
            <a class="gray min-w75 center block" href="<?= url('register'); ?>">
              <?= __('app.registration'); ?>
            </a>
          <?php endif; ?>
          <a class="btn min-w75 btn-outline-primary" href="<?= url('login'); ?>">
            <?= __('app.sign_in'); ?>
          </a>
        </div>
      <?php else : ?>

        <div class="flex gap-max items-center">

          <?= Html::addPost($facet); ?>

          <div id="toggledark"><svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#sun"></use>
            </svg></div>

          <a id="notif" class="gray-600 relative" href="<?= url('notifications'); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#bell"></use>
            </svg>
            <span class="number"></span>
          </a>

          <div class="relative">
            <div class="trigger">
              <?= Img::avatar(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base', 'small'); ?>
            </div>
            <ul class="dropdown user">
              <?= insert('/_block/navigation/menu-user', ['type' => $type, 'list' => config('navigation/menu.user')]); ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </header>

  <div id="contentWrapper">