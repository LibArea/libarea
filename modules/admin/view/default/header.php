<?php
$dark = Request::getCookie('dayNight') == 'dark' ? 'dark' : '';
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

<body class="body-bg-fon<?php if ($dark == 'dark') { ?> dark<?php } ?>">

  <header class="bg-white box-shadow sticky top0 z-30">
    <div class="box-flex-white pl10 pr10 h50">
      <div class="flex items-center w200">
        <a class="ml5 sky-500" href="/">
          <i class="bi bi-house"></i>
        </a>
        <span class="mr5 ml5">/</span>
        <a href="<?= getUrlByName('admin'); ?>">
          <span class="black"><?= Translate::get('admin'); ?></span>
        </a>
      </div>
      <div class="ml45 mr30 relative mb-none w-90">
        <form class="form" method="post" action="<?= getUrlByName('search'); ?>">
          <input type="text" autocomplete="off" name="q" id="find" placeholder="<?= Translate::get('to find'); ?>" class="h30 bg-gray-100 p10 br-rd5 gray w-100">
          <input name="token" value="<?= csrf_token(); ?>" type="hidden">
          <input name="url" value="<?= AG_PATH_FACETS_LOGOS; ?>" type="hidden">
        </form>
      </div>
      <div class="black m15">
        <?= Request::getRemoteAddress(); ?>
      </div>
    </div>
  </header>
  <div id="contentWrapper">