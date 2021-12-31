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

<?php
$dark = Request::getCookie('dayNight') == 'dark' ? 'dark' : '';
$css = $data['type'] == 'web' || $data['type'] == 'page'  ? 'p0 m0 black' : 'p0 m0 black bg-gray-100';
$type  = $data['type'] ?? false;
$facet = $data['facet'] ?? false; ?>

<body class="<?= $css; ?><?php if (Request::getCookie('dayNight') == 'dark') { ?> dark<?php } ?>">

  <header class="bg-white box-shadow mt0 mb15 <?php if ($type != 'page') { ?>sticky top0<?php } ?> z-30">
    <div class="col-span-12 mr-auto max-width w-100 pr10 pl10 h44 grid items-center flex justify-between">
      <div class="flex items-center">
        <ag-menu data-template="one" class="tippy pl0 pr10 no-pc">
          <div class="relative w-auto">
            <i class="bi bi-list gray-400 text-xl"></i>
          </div>
         </ag-menu> 
          <div id="one" style="display: none;" class="box-shadow2 min-w165 z-40 bg-white br-rd3">
            <nav>
              <?= tabs_nav(
                'menu',
                $type,
                $uid,
                $pages = Config::get('menu.mobile'),
              ); ?>
            </nav>
          </div>
         
        <div class="mr20 flex items-center">
          <a title="<?= Translate::get('home'); ?>" class="text-2xl mb-text-xl sky-500-hover p5 black dark-gray-300 uppercase" href="/">
            <?= Config::get('meta.name'); ?>
          </a>
        </div>
      </div>
      <?php if (Request::getUri() != getUrlByName('search')) { ?>
        <div class="p5 ml30 mr20 relative no-mob w-100">
          <form class="form" method="post" action="<?= getUrlByName('search'); ?>">
            <input type="text" autocomplete="off" name="q" id="find" placeholder="<?= Translate::get('to find'); ?>" class="h30 bg-gray-100 p15 br-rd20 gray w-100">
            <input name="token" value="<?= csrf_token(); ?>" type="hidden">
          </form>
          <div class="absolute box-shadow bg-white pt10 pr15 pb5 pl15 mt5 max-w460 br-rd3 none" id="search_items"></div>
        </div>
      <?php } ?>
      <?php if ($uid['user_id'] == 0) { ?>
        <div class="flex right col-span-4 items-center">
          <div id="toggledark" class="header-menu-item no-mob only-icon p10 ml30 mb-ml-10">
            <i class="bi bi-brightness-high gray-400 text-xl"></i>
          </div>
          <?php if (Config::get('general.invite') == 0) { ?>
            <a class="register gray ml30 mr15 mb-ml-10 mb-mr-5 block" title="<?= Translate::get('sign up'); ?>" href="<?= getUrlByName('register'); ?>">
              <?= Translate::get('sign up'); ?>
            </a>
          <?php } ?>
          <a class="btn btn-outline-primary ml20" title="<?= Translate::get('sign in'); ?>" href="<?= getUrlByName('login'); ?>">
            <?= Translate::get('sign in'); ?>
          </a>
        </div>
      <?php } else { ?>
        <div class="col-span-4">
          <div class="flex right ml30 mb-ml-10 items-center">

            <?= add_post($facet, $uid['user_id']); ?>

            <div id="toggledark" class="only-icon p10 ml20 mb-ml-10">
              <i class="bi bi-brightness-high gray-400 text-xl"></i>
            </div>

            <a class="gray-400 p10 text-xl ml20 mb-ml-10" href="<?= getUrlByName('user.notifications', ['login' => $uid['user_login']]); ?>">
              <?php $notif = \App\Controllers\NotificationsController::setBell($uid['user_id']); ?>
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

            <ag-menu data-template="two" class="tippy pr10 pl0 ml20 mb-ml-10">
              <div class="relative w-auto">
                <?= user_avatar_img($uid['user_avatar'], 'small', $uid['user_login'], 'w34 br-rd-50'); ?>
              </div>
            </ag-menu>  
            <div id="two" style="display: none;" class="bg-white br-rd3">
              <nav class="p0 pr20 m0">
                <?= tabs_nav(
                  'menu',
                  $type,
                  $uid,
                  $pages = Config::get('menu.user'),
                ); ?>
              </nav>
            </div>
             
          </div>
        </div>
      <?php }  ?>
    </div>
  </header>
  <div class="max-width mr-auto grid grid-cols-12 gap-4 pr5 pl5">