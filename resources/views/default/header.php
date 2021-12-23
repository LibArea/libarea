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

<?php $type  = $data['type'] ?? false;
      $facet = $data['facet'] ?? false; ?>

<body class="p0 m0 black bg-gray-100<?php if (Request::getCookie('dayNight') == 'dark') { ?> dark<?php } ?>">

  <header class="bg-white br-bottom mt0 mb15 <?php if ($type != 'page') { ?>sticky top0<?php } ?> z-30">
    <div class="col-span-12 mr-auto max-width w-100 pr10 pl10 h44 grid items-center flex justify-between">
      <div class="flex items-center">
        <div class="lateral no-pc mr10 flex size-15">
          <i class="bi bi-list gray-light-2 size-18"></i>
          <nav class="ltr-menu box-shadow none min-w165 bg-white br-rd3 absolute pl0 sticky">
            <?php foreach (Config::get('menu-header-user-mobile') as $menu) { ?>
              <a class="pt5 pr10 pb5 pl10 gray block bg-hover-light" href="<?= $menu['url']; ?>">
                <i class="<?= $menu['icon']; ?> middle"></i>
                <span class="ml5"><?= $menu['name']; ?></span>
              </a>
            <?php } ?>
          </nav>
        </div>
        <div class="mr20 flex items-center">
          <a title="<?= Translate::get('home'); ?>" class="size-21 mb-size-18 p5 black dark-white uppercase" href="/">
            <?= Config::get('meta.name'); ?>
          </a>
        </div>
      </div>
      <?php if (Request::getUri() != getUrlByName('search')) { ?>
        <div class="p5 ml30 mr20 relative no-mob w-100">
          <form class="form" method="post" action="<?= getUrlByName('search'); ?>">
            <input type="text" autocomplete="off" name="q" id="find" placeholder="<?= Translate::get('to find'); ?>" class="h30 bg-gray-100 size-15 p15 br-rd20 gray w-100">
            <input name="token" value="<?= csrf_token(); ?>" type="hidden">
          </form>
          <div class="absolute box-shadow bg-white pt10 pr15 pb5 pl15 mt5 max-w460 br-rd3 none" id="search_items"></div>
        </div>
      <?php } ?>
      <?= import('/_block/menu/header-user-menu', ['uid' => $uid, 'facet' => $facet ?? null]); ?>
    </div>
  </header>
  <div class="max-width mr-auto w-100 grid grid-cols-12 gap-4 pr5 pl5 justify-between">
  