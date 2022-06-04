<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?04');
Request::getHead()->addStyles('/assets/css/test.css?04');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body class="body-test<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?><?php if (Request::getCookie('menuYesNo') == 'menuno') : ?> menuno<?php endif; ?>">
  <header class="text-header">
    <div class="d-header_contents justify-between">
      <div class="flex items-center">
        <a title="<?= __('app.home'); ?>" class="logo" href="/">
          <?= config('meta.name'); ?>
        </a>


        <ul class="nav ml30">
          <?php $sheet = $data['sheet'] ?? false; ?>
          <?= insert('/_block/navigation/nav', ['list' => config('navigation/nav.home')]); ?>
        </ul>
      </div>

      <?php if (!UserData::checkActiveUser()) : ?>
        <div class="flex gap-max items-center">
          <div id="toggledark" class="header-menu-item mb-none">
            <i class="bi-brightness-high gray-600 text-xl"></i>
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

          <div id="toggledark" class="only-icon">
            <i class="bi-brightness-high gray-600"></i>
          </div>

          <a class="gray-600" href="<?= url('notifications'); ?>">
            <?php $notif = \App\Controllers\NotificationController::setBell(UserData::getUserId()); ?>
            <?php if (!empty($notif)) : ?>
              <?php if ($notif['action_type'] == 1) : ?>
                <i class="bi-envelope red"></i>
              <?php else : ?>
                <i class="bi-bell-fill red"></i>
              <?php endif; ?>
            <?php else : ?>
              <i class="bi-bell"></i>
            <?php endif; ?>
          </a>

          <div class="relative">
            <div class="trigger">
              <?= Html::image(UserData::getUserAvatar(), UserData::getUserLogin(), 'img-base mb-pr0', 'avatar', 'small'); ?>
            </div>
            <ul class="dropdown">
              <?= insert('/_block/navigation/menu', ['type' => $type, 'list' => config('navigation/menu.user')]); ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </header>

  <div id="contentWrapper">