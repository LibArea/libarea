<?php
Request::getHead()->addStyles('/assets/css/style.css?01');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= Tpl::import('/meta', ['meta' => $meta]); ?>

<body<?php if (Request::getCookie('dayNight') == 'dark') { ?> class="dark" <?php } ?>>

  <header class="bg-white mt0 mb15">
    <div class="br-bottom mr-auto max-width w-100 pr10 pl15 mb10 mb-none items-center flex">
      <a class="mr20 black text-xs" href="/topics">
        <i class="bi-columns-gap mr5"></i> <?= Translate::get('topics'); ?>
      </a>
      <a class="mr20 black text-xs" href="/blogs">
        <i class="bi-journals mr5"></i> <?= Translate::get('blogs'); ?>
      </a>
      <a class="mr20 black text-xs" href="/users">
        <i class="bi-people mr5"></i> <?= Translate::get('users'); ?>
      </a>
      <a class="mr20 black text-xs" href="/web">
        <i class="bi-link-45deg mr5"></i> <?= Translate::get('catalog'); ?>
      </a>
      <a class="mr20 black text-xs" href="/search">
        <i class="bi-search mr5"></i> <?= Translate::get('search'); ?>
      </a>
    </div>

    <div class="wrap items-center flex justify-between">
      <div class="flex items-center" id="find">
        <div class="none mb-block">
          <div class="trigger">
            <i class="bi-list gray-400 text-xl mr10"></i>
          </div>
          <ul class="dropdown left">
            <?= tabs_nav(
              'menu',
              $type,
              $user,
              $pages = Config::get('menu.user')
            ); ?>
          </ul>
        </div>
        <div class="ml20 flex items-center">
          <a title="<?= Translate::get('home'); ?>" class="logo ml5" href="/">
            <?= Config::get('meta.name'); ?>
          </a>
        </div>
      </div>

      <?php if ($user['id'] == 0) { ?>
        <div class="flex right items-center">
          <div id="toggledark" class="header-menu-item mb-none only-icon p10 ml30 mb-ml10">
            <i class="bi-brightness-high gray-400 text-xl"></i>
          </div>
          <?php if (Config::get('general.invite') == false) { ?>
            <a class="w94 gray ml30 mr15 mb-ml10 mb-mr5 block" href="<?= getUrlByName('register'); ?>">
              <?= Translate::get('registration'); ?>
            </a>
          <?php } ?>
          <a class="w94 btn btn-outline-primary ml20" href="<?= getUrlByName('login'); ?>">
            <?= Translate::get('sign.in'); ?>
          </a>
        </div>
      <?php } else { ?>
        <div>
          <div class="flex right ml30 mb-ml10 items-center">

            <?= add_post($facet, $user['id']); ?>

            <div id="toggledark" class="only-icon p10 ml20 mb-ml10">
              <i class="bi-brightness-high gray-400 text-xl"></i>
            </div>

            <a class="gray-400 p10 text-xl ml20 mb-ml10" href="<?= getUrlByName('notifications'); ?>">
              <?php $notif = \App\Controllers\NotificationController::setBell($user['id']); ?>
              <?php if (!empty($notif)) { ?>
                <?php if ($notif['action_type'] == 1) { ?>
                  <i class="bi-envelope red"></i>
                <?php } else { ?>
                  <i class="bi-bell-fill red"></i>
                <?php } ?>
              <?php } else { ?>
                <i class="bi-bell"></i>
              <?php } ?>
            </a>

            <div class="ml45 mb-ml20 relative">
              <div class="trigger">
                <?= user_avatar_img($user['avatar'], 'small', $user['login'], 'ava-base'); ?>
              </div>
              <ul class="dropdown">
                <?= tabs_nav(
                  'menu',
                  $type,
                  $user,
                  $pages = Config::get('menu.user')
                ); ?>
              </ul>
            </div>

          </div>
        </div>
      <?php }  ?>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">