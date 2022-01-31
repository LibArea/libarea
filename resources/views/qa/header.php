<?php
$type   = $data['type'] ?? false;
$facet  = $data['facet'] ?? false; ?>

<!DOCTYPE html>
<html lang="<?= Translate::getLang(); ?>" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= $meta; ?>
  <?php getRequestHead()->output(); ?>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="icon" sizes="16x16" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" sizes="120x120" href="/favicon-120.ico" type="image/x-icon">
</head>

<body<?php if (Request::getCookie('dayNight') == 'dark') { ?> class="dark"<?php } ?>>

  <header class="bg-white mt0 mb15">
    <div class="br-bottom mr-auto max-w1240 w-100 pr10 pl15 mb10 mb-none items-center flex">
      <?php foreach (Config::get('menu.mobile') as $key => $topic) { ?>
        <a class="mr20 black text-xs" href="<?= $topic['url']; ?>">
          <i class="<?= $topic['icon']; ?> mr5"></i>
          <span><?= $topic['title']; ?> </span>
        </a>
      <?php } ?>
    </div>

    <div class="col-span-12 mr-auto max-w1240 w-100 pr10 pl10 h50 grid items-center flex justify-between">
      <div class="flex items-center" id="find">
        <menu data-template="one" class="tippy pl0 pr10 none mb-block">
          <div class="relative w-auto">
            <i class="bi bi-list gray-400 text-xl"></i>
          </div>
         </menu> 
          <div id="one" style="display: none;" class="box-shadow2 z-40 bg-white br-rd3">
            <nav>
              <?= tabs_nav(
                'menu',
                $type,
                $user,
                $pages = Config::get('menu.mobile'),
              ); ?>
            </nav>
          </div>
        <div class="mr20 flex items-center">
          <a title="<?= Translate::get('home'); ?>" class="logo black ml5" href="/">
            <?= Config::get('meta.name'); ?>
          </a>
        </div>
      </div>

      <?php if ($user['id'] == 0) { ?>
        <div class="flex right col-span-4 items-center">
          <div id="toggledark" class="header-menu-item mb-none only-icon p10 ml30 mb-ml-10">
            <i class="bi bi-brightness-high gray-400 text-xl"></i>
          </div>
          <?php if (Config::get('general.invite') == false) { ?>
            <a class="register gray ml30 mr15 mb-ml-10 mb-mr-5 block" title="<?= Translate::get('sign up'); ?>" href="<?= getUrlByName('register'); ?>">
              <?= Translate::get('sign up'); ?>
            </a>
          <?php } ?>
          <a class="btn btn-outline-primary ml20" title="<?= Translate::get('sign.in'); ?>" href="<?= getUrlByName('login'); ?>">
            <?= Translate::get('sign.in'); ?>
          </a>
        </div>
      <?php } else { ?>
        <div class="col-span-4">
          <div class="flex right ml30 mb-ml-10 items-center">

            <?= add_post($facet, $user['id']); ?>

            <div id="toggledark" class="only-icon p10 ml20 mb-ml-10">
              <i class="bi bi-brightness-high gray-400 text-xl"></i>
            </div>

            <a class="gray-400 p10 text-xl ml20 mb-ml-10" href="<?= getUrlByName('notifications'); ?>">
              <?php $notif = \App\Controllers\NotificationsController::setBell($user['id']); ?>
              <?php if (!empty($notif)) { ?>
                <?php if ($notif['notification_action_type'] == 1) { ?>
                  <i class="bi bi-envelope red-500"></i>
                <?php } else { ?>
                  <i class="bi bi-bell-fill red-500"></i>
                <?php } ?>
              <?php } else { ?>
                <i class="bi bi-bell"></i>
              <?php } ?>
            </a>

            <ag-menu data-template="two" class="tippy pr10 pl10 ml20 mb-ml-10">
              <div class="relative w-auto">
                <?= user_avatar_img($user['avatar'], 'small', $user['login'], 'w30 h30 br-rd-50'); ?>
              </div>
            </ag-menu>  
            <div id="two" style="display: none;" class="bg-white br-rd3">
              <nav class="p0 pr20 m0">
                <?= tabs_nav(
                  'menu',
                  $type,
                  $user,
                  $pages = Config::get('menu.user'),
                ); ?>
              </nav>
            </div>
            
          </div>
        </div>
      <?php }  ?>
    </div>
  </header>
  <div id="contentWrapper">