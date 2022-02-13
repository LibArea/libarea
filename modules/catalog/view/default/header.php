<?php
Translate::setLang($user['lang']);
$dark     = Request::getCookie('dayNight') == 'dark' ? 'dark' : '';
?>

<!DOCTYPE html>
<html lang="<?= Translate::getLang(); ?>" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= $meta; ?>
  <?php getRequestHead()->output(); ?>
  <link rel="stylesheet" href="/assets/css/style.css?03">
  <link rel="icon" sizes="16x16" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" sizes="120x120" href="/favicon-120.ico" type="image/x-icon">
</head>

<body <?php if ($dark == 'dark') { ?>class="dark" <?php } ?>>
  <header>
    <div class="mr-auto item-search mb-p10">
      <a class="logo sky-500 mt30 mb-none" href="<?= getUrlByName('web'); ?>">
        <?= Translate::get('catalog'); ?>
      </a>
      <div class="w-100 ml45 mb-ml0">
        <div data-template="one" id="find tippy">
          <a class="item-search__url" href="/"><?= Config::get('meta.name'); ?></a>
          <div class="flex right col-span-4 items-center">
            <div id="toggledark" class="header-menu-item mb-none only-icon mr30 mb-ml10">
              <i class="bi bi-brightness-high gray-400 text-xl"></i>
            </div>
            <?php if (!UserData::checkActiveUser()) { ?>
              <?php if (Config::get('general.invite') == false) { ?>
                <a class="register gray-400 mr15 mb-ml10 mb-mr5 block" title="<?= Translate::get('sign up'); ?>" href="<?= getUrlByName('register'); ?>">
                  <?= Translate::get('sign up'); ?>
                </a>
              <?php } ?>
              <a class="gray-400 mr10 ml10" title="<?= Translate::get('sign.in'); ?>" href="<?= getUrlByName('login'); ?>">
                <?= Translate::get('sign.in'); ?>
              </a>
            <?php } else { ?>
              <?php if (UserData::checkAdmin()) { ?>
                <div class="relative mr30 gray-400">
                  <div class="trigger">
                    <?= Translate::get('menu'); ?>
                  </div>
                  <ul class="dropdown nav-catalog pl10">
                    <?= tabs_nav(
                      'menu',
                      'admin',
                      $user,
                      $pages = Config::get('menu.catalog')
                    ); ?>
                  </ul>
                  </div>
              <?php } ?>
                <a class="<?php if ($data['sheet'] == 'web.bookmarks') { ?>bg-gray-100 p5 gray-600 <?php } ?>mr30 green-600" href="<?= getUrlByName('web.bookmarks'); ?>">
                  <?= Translate::get('favorites'); ?>
                </a>
                <div class="mr15 m relative">
                  <div class="trigger">
                    <?= $user['login']; ?>
                  </div>
                  <ul class="dropdown">
                    <?= tabs_nav(
                      'menu',
                      'dir',
                      $user,
                      $pages = Config::get('menu.user')
                    ); ?>
                  </ul>
                </div>
            <?php } ?>
          </div>
        </div>
        <form method="post" action="<?= getUrlByName('web.search'); ?>">
          <input type="text" name="q" placeholder="<?= Translate::get('to find'); ?>" class="item-search__input">
          <input name="token" value="<?= csrf_token(); ?>" type="hidden">
          <input name="url" value="<?= AG_PATH_FACETS_LOGOS; ?>" type="hidden">
        </form>
      </div>
    </div>
  </header>