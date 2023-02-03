<?php

use Hleb\Constructor\Handlers\Request;

Request::getHead()->addStyles('/assets/css/style.css?14');
Request::getHead()->addStyles('/assets/css/qa.css?14');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= insert('/meta', ['meta' => $meta]); ?>

<body<?php if (Request::getCookie('dayNight') == 'dark') : ?> class="dark" <?php endif; ?>>

  <header class="bg-white mb10">
    <div class="br-bottom wrap mb10 mb-none items-center flex gap">
      <a class="p5 black text-xs" href="/topics">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#hash"></use>
        </svg> <?= __('app.topics'); ?>
      </a>
      <a class="black text-xs" href="/blogs">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#post"></use>
        </svg> <?= __('app.blogs'); ?>
      </a>
      <a class="black text-xs" href="/users">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#users"></use>
        </svg> <?= __('app.users'); ?>
      </a>
      <a class="black text-xs" href="/web">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#link"></use>
        </svg> <?= __('app.catalog'); ?>
      </a>
      <a class="black text-xs" href="/search">
        <svg class="icons icon-small">
          <use xlink:href="/assets/svg/icons.svg#search"></use>
        </svg> <?= __('app.search'); ?>
      </a>
    </div>

    <div class="wrap items-center flex justify-between">
      <div class="flex items-center" id="find">
        <a title="<?= __('app.home'); ?>" class="logo ml5" href="/">
          <?= config('meta.name'); ?>
        </a>
      </div>

      <?php if (!UserData::checkActiveUser()) : ?>
        <div class="flex gap-max items-center">
          <a id="toggledark" class="header-menu-item gray-600 mb-none">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#sun"></use>
            </svg>
          </a>
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
        <div>
          <div class="flex gap-max items-center">

            <?= Html::addPost($facet); ?>

            <div id="toggledark" class="only-icon">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#sun"></use>
              </svg>
            </div>

            <a id="notif" class="gray-600" href="<?= url('notifications'); ?>">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#bell"></use>
              </svg>
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
        </div>
      <?php endif;  ?>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">