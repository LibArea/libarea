<?php
Request::getHead()->addStyles('/assets/css/style.css?16');
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<?= Tpl::import('/meta', ['meta' => $meta]); ?>

<body<?php if (Request::getCookie('dayNight') == 'dark') { ?> class="dark"<?php } ?>>

  <header class="bg-white mt0 mb15">
    <div class="br-bottom mr-auto max-width w-100 pr10 pl15 mb10 mb-none items-center flex">
      <?php foreach (Config::get('menu.mobile') as $key => $topic) { ?>
        <a class="mr20 black text-xs" href="<?= $topic['url']; ?>">
          <i class="<?= $topic['icon']; ?> mr5"></i>
          <span><?= $topic['title']; ?> </span>
        </a>
      <?php } ?>
    </div>

    <div class="mr-auto max-width w-100 pr10 pl10 items-center flex justify-between">
      <div class="flex items-center" id="find">
        <div class="none mb-block">
          <div class="trigger">
            <i class="bi bi-list gray-400 text-xl mr10"></i>
          </div>
          <ul class="dropdown left">
            <?= tabs_nav(
              'menu',
              $type,
              $user,
              $pages = Config::get('menu.mobile')
            ); ?>
          </ul>
        </div>
        <div class="mr20 flex items-center">
          <a title="<?= Translate::get('home'); ?>" class="logo black ml5" href="/">
            <?= Config::get('meta.name'); ?>
          </a>
        </div>
      </div>

      <?php if ($user['id'] == 0) { ?>
        <div class="flex right col-span-4 items-center">
          <div id="toggledark" class="header-menu-item mb-none only-icon p10 ml30 mb-ml10">
            <i class="bi bi-brightness-high gray-400 text-xl"></i>
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
        <div class="col-span-4">
          <div class="flex right ml30 mb-ml10 items-center">

            <?= add_post($facet, $user['id']); ?>

            <div id="toggledark" class="only-icon p10 ml20 mb-ml10">
              <i class="bi bi-brightness-high gray-400 text-xl"></i>
            </div>

            <a class="gray-400 p10 text-xl ml20 mb-ml10" href="<?= getUrlByName('notifications'); ?>">
              <?php $notif = \App\Controllers\NotificationController::setBell($user['id']); ?>
              <?php if (!empty($notif)) { ?>
                <?php if ($notif['action_type'] == 1) { ?>
                  <i class="bi bi-envelope red-500"></i>
                <?php } else { ?>
                  <i class="bi bi-bell-fill red-500"></i>
                <?php } ?>
              <?php } else { ?>
                <i class="bi bi-bell"></i>
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
  <div id="contentWrapper">