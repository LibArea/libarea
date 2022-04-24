<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?12');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= Tpl::insert('/meta', ['meta' => $meta]); ?>

<body class="body-bg-fon<?php if (Request::getCookie('dayNight') == 'dark') : ?> dark<?php endif; ?><?php if (Request::getCookie('menuYesNo') == 'menuno') : ?> menuno<?php endif; ?>">
  <header class="d-header">
    <div class="wrap">
      <div class="d-header_contents">

        <div id="togglemenu" class="mb-none mr10">
          <i class="bi-list gray-600 text-xl"></i>
        </div>

        <div class="menu__button none mb-block mr10">
          <i class="bi-list gray-600 text-xl"></i>
        </div>

        <a title="<?= __('home'); ?>" class="logo" href="/">
          <?= Config::get('meta.name'); ?>
        </a>

        <div class="ml45 mb-ml10 relative w-100">
          <form class="form mb-none" method="get" action="<?= getUrlByName('search.go'); ?>">
            <input type="text" name="q" autocomplete="off" id="find" placeholder="<?= __('to.find'); ?>" class="search">
          </form>
          <div class="absolute box-shadow bg-white p15 pt0 mt5 br-rd3 none" id="search_items"></div>
        </div>

        <?php if (!UserData::checkActiveUser()) : ?>
          <div class="flex right items-center">
            <div id="toggledark" class="header-menu-item mb-none ml45">
              <i class="bi-brightness-high gray-600 text-xl"></i>
            </div>
            <?php if (Config::get('general.invite') == false) : ?>
              <a class="w94 gray ml45 mr15 mb-mr5 mb-ml5 block" href="<?= getUrlByName('register'); ?>">
                <?= __('registration'); ?>
              </a>
            <?php endif; ?>
            <a class="w94 btn btn-outline-primary ml20" href="<?= getUrlByName('login'); ?>">
              <?= __('sign.in'); ?>
            </a>
          </div>
        <?php else : ?>

          <div class="flex right ml45 mb-ml0 items-center text-xl">

            <?= Html::addPost($facet, $user['id']); ?>

            <div id="toggledark" class="only-icon ml45 mb-ml20">
              <i class="bi-brightness-high gray-600"></i>
            </div>

            <a class="gray-600 ml45 mb-ml20" href="<?= getUrlByName('notifications'); ?>">
              <?php $notif = \App\Controllers\NotificationController::setBell($user['id']); ?>
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

            <div class="ml45 mb-ml20">
              <div class="trigger">
                <?= Html::image($user['avatar'], $user['login'], 'ava-base', 'avatar', 'small'); ?>
              </div>
              <ul class="dropdown">
                <?= Tpl::insert('/_block/navigation/menu', ['type' => $type, 'user' => $user, 'list' => Config::get('navigation/menu.user')]); ?>
              </ul>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <?php if ($user['id'] == 0 && $data['type'] == 'main') : ?>
    <div class="box mb-none center">
      <h1><?= Config::get('meta.banner_title'); ?></h1>
      <p><?= Config::get('meta.banner_desc'); ?>...</p>
    </div>
  <?php endif; ?>

  <div id="contentWrapper" class="wrap">

    <?= Tpl::insert('/menu', ['data' => $data, 'user' => $user]); ?>