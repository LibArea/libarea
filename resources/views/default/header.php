<?php
$dark     = Request::getCookie('dayNight') == 'dark' ? 'dark' : '';
$css      = $data['type'] == 'web' || $data['type'] == 'page'  ? '' : 'body-bg-fon';
$type     = $data['type'] ?? false;
$facet    = $data['facet'] ?? false;
?>

<!DOCTYPE html>
<html lang="<?= Translate::getLang(); ?>" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= $meta; ?>
  <?php getRequestHead()->output(); ?>
  <link rel="stylesheet" href="/assets/css/style.css?12">
  <link rel="icon" sizes="16x16" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" sizes="120x120" href="/favicon-120.ico" type="image/x-icon">
</head>

<body class="<?= $css; ?><?php if ($dark == 'dark') { ?> dark<?php } ?>">

  <header class="d-header<?php if ($type != 'page') { ?> sticky top0<?php } ?>">
    <div class="wrap">
      <div class="d-header_contents">

        <div class="none mb-block">
          <div class="trigger mr10">
            <i class="bi bi-list gray-400 text-xl"></i>
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

        <a title="<?= Translate::get('home'); ?>" class="logo black" href="/">
          <?= Config::get('meta.name'); ?>
        </a>

        <div class="ml45 relative w-100">
            <form class="form mb-none" method="get" action="<?= getUrlByName('search'); ?>">
              <input type="text" name="q" autocomplete = "off" id="find" placeholder="<?= Translate::get('to find'); ?>" class="search">
              <input name="type" value="post" type="hidden">
            </form>
            <div class="absolute box-shadow bg-white p15 pt0 mt5 br-rd3 none" id="search_items"></div>
        </div>

        <?php if (!UserData::checkActiveUser()) { ?>
          <div class="flex right col-span-4 items-center">
            <div id="toggledark" class="header-menu-item mb-none ml45">
              <i class="bi bi-brightness-high gray-400 text-xl"></i>
            </div>
            <?php if (Config::get('general.invite') == false) { ?>
              <a class="w94 gray ml45 mr15 mb-mr5 block" href="<?= getUrlByName('register'); ?>">
                <?= Translate::get('registration'); ?>
              </a>
            <?php } ?>
            <a class="w94 btn btn-outline-primary ml20" href="<?= getUrlByName('login'); ?>">
              <?= Translate::get('sign.in'); ?>
            </a>
          </div>
        <?php } else { ?>

          <div class="flex right ml45 mb-ml0 items-center text-xl">

            <?= add_post($facet, $user['id']); ?>

            <div id="toggledark" class="only-icon ml45 mb-ml20">
              <i class="bi bi-brightness-high gray-400"></i>
            </div>

            <a class="gray-400 ml45 mb-ml20" href="<?= getUrlByName('notifications'); ?>">
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

            <div class="ml45 mb-ml20">
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
        <?php } ?>
      </div>
    </div>
  </header>
  <div id="contentWrapper">