<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?01');
Request::getHead()->addStyles('/assets/css/test.css?01');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="body-test<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?><?php if (Request::getCookie('menuYesNo') == 'menuno') : ?> menuno<?php endif; ?>">
  <header class="text-header wrap">
    <div class="d-header_contents justify-between">

      <div class="flex gap-max items-center">
        <a title="<?= __('app.home'); ?>" class="logo" href="/">
          <?= config('meta.name'); ?>
        </a>
        <ul class="nav">
          <?php $sheet = $data['sheet'] ?? false; ?>
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
            <a class="w94 gray block" href="<?= url('register'); ?>">
              <?= __('app.registration'); ?>
            </a>
          <?php endif; ?>
          <a class="w94 btn btn-outline-primary" href="<?= url('login'); ?>">
            <?= __('app.sign_in'); ?>
          </a>
        </div>
      <?php else : ?>

        <div class="flex gap-max items-center">

          <?= Html::addPost($facet); ?>

          <div id="toggledark"><svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#sun"></use>
            </svg></div>

          <a class="gray-600" href="<?= url('notifications'); ?>">
            <?php $notif = \App\Controllers\NotificationController::setBell(UserData::getUserId()); ?>
            <?php if (!empty($notif)) : ?>
              <?php if ($notif['action_type'] == 1) : ?>
                <svg class="icons red">
                  <use xlink:href="/assets/svg/icons.svg#mail"></use>
                </svg>
              <?php else : ?>
                <svg class="icons red">
                  <use xlink:href="/assets/svg/icons.svg#bell"></use>
                </svg>
              <?php endif; ?>
            <?php else : ?>
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#bell"></use>
              </svg>
            <?php endif; ?>
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