<?php
$user = App\Middleware\Before\UserData::get();
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
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="icon" sizes="16x16" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" sizes="120x120" href="/favicon-120.ico" type="image/x-icon">
</head>

<body class="bg-fons<?php if ($dark == 'dark') { ?> dark<?php } ?>">

  <header class="bg-blue-steel-700 sticky top0 mt0">
    <div class="flex justify-between items-center max-width mr-auto h40">
      <div class="flex items-center">
        <div class="w200 white">
          <a class="ml20" href="/">
            <i class="bi bi-house white"></i>
          </a>
          <span class="mr5 ml5">/</span>
          <a href="<?= getUrlByName('admin'); ?>">
            <span class="white"><?= Translate::get('admin'); ?><span>
          </a>
        </div>
        <div class="w400 items-center">
          <form class="form" method="post" action="<?= getUrlByName('search'); ?>">
            <input type="text" autocomplete="off" name="q" id="find" placeholder="<?= Translate::get('to find'); ?>" class="h30 bg-gray-100 p10 br-rd5 gray w-100">
            <input name="token" value="<?= csrf_token(); ?>" type="hidden">
            <input name="url" value="<?= AG_PATH_FACETS_LOGOS; ?>" type="hidden">
          </form>
        </div>
      </div>
      <div class="white mr15">
        <?= Request::getRemoteAddress(); ?>
      </div>
    </div>
  </header>
  <div id="contentWrapper">