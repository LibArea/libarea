<?php
Request::getHead()->addStyles('/assets/css/style.css?12');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= Tpl::insert('/meta', ['meta' => $meta]); ?>

<body<?php if (Request::getCookie('dayNight') == 'dark') : ?> class="dark" <?php endif; ?>>

  <header class="bg-white mt0 mb15">
    <div class="br-bottom mr-auto max-width w-100 pr10 pl15 mb10 mb-none items-center flex">
      <a class="mr20 black text-xs" href="/topics">
        <i class="bi-columns-gap mr5"></i> <?= __('topics'); ?>
      </a>
      <a class="mr20 black text-xs" href="/blogs">
        <i class="bi-journals mr5"></i> <?= __('blogs'); ?>
      </a>
      <a class="mr20 black text-xs" href="/users">
        <i class="bi-people mr5"></i> <?= __('users'); ?>
      </a>
      <a class="mr20 black text-xs" href="/web">
        <i class="bi-link-45deg mr5"></i> <?= __('catalog'); ?>
      </a>
      <a class="mr20 black text-xs" href="/search/go">
        <i class="bi-search mr5"></i> <?= __('search'); ?>
      </a>
    </div>

    <div class="wrap items-center flex justify-between">
      <div class="flex items-center" id="find">
        <div class="ml20 flex items-center">
          <a title="<?= __('home'); ?>" class="logo ml5" href="/">
            <?= Config::get('meta.name'); ?>
          </a>
        </div>
      </div>

      <?php if (!UserData::checkActiveUser()) : ?>
        <div class="flex right items-center">
          <div id="toggledark" class="header-menu-item mb-none only-icon p10 ml30 mb-ml10">
            <i class="bi-brightness-high gray-600 text-xl"></i>
          </div>
          <?php if (Config::get('general.invite') == false) : ?>
            <a class="w94 gray ml30 mr15 mb-ml10 mb-mr5 block" href="<?= getUrlByName('register'); ?>">
              <?= __('registration'); ?>
            </a>
          <?php endif; ?>
          <a class="w94 btn btn-outline-primary ml20" href="<?= getUrlByName('login'); ?>">
            <?= __('sign.in'); ?>
          </a>
        </div>
      <?php else : ?>
        <div>
          <div class="flex right ml30 mb-ml10 items-center">

            <?= Html::addPost($facet, $user['id']); ?>

            <div id="toggledark" class="only-icon p10 ml20 mb-ml10">
              <i class="bi-brightness-high gray-600 text-xl"></i>
            </div>

            <a class="gray-600 p10 text-xl ml20 mb-ml10" href="<?= getUrlByName('notifications'); ?>">
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

            <div class="ml45 mb-ml20 relative">
              <div class="trigger">
                <?= Html::image($user['avatar'], $user['login'], 'ava-base', 'avatar', 'small'); ?>
              </div>
              <ul class="dropdown">
                <?= Tpl::insert('/_block/navigation/menu', ['type' => $type, 'user' => $user, 'list' => Config::get('navigation/menu.user')]); ?>
              </ul>
            </div>

          </div>
        </div>
      <?php endif;  ?>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">