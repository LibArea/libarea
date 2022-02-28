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
  <link rel="stylesheet" href="/assets/css/style.css?12">
  <link rel="icon" sizes="16x16" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" sizes="120x120" href="/favicon-120.ico" type="image/x-icon">
</head>

<body class="body-bg-fon<?php if ($dark == 'dark') { ?> dark<?php } ?>">

  <header class="d-header sticky top0">
    <div class="wrap">
      <div class="d-header_contents">
        <div class="flex items-center w200">
          <a href="<?= getUrlByName('admin'); ?>">
            <span class="black"><?= Translate::get('admin'); ?></span>
          </a>
        </div>
        <div class="relative w-90">
          <a class="gray-400" href="<?= getUrlByName('admin.all.structure'); ?>">
            <i class="bi bi-columns-gap middle mr5 text-sm"></i>
            <span class="mb-none"><?= Translate::get('structure'); ?></span>
          </a>
          <a class="gray-400 ml30" href="<?= getUrlByName('admin.tools'); ?>">
            <i class="bi bi-tools middle mr5 text-sm"></i>
            <span class="mb-none"><?= Translate::get('tools'); ?></span>
          </a>
        </div>
        <div class="m15 gray-400 mb-none">
          <?= Request::getRemoteAddress(); ?>
        </div>
          <a class="ml5 sky-500" href="/">
            <i class="bi bi-house"></i>
          </a>
      </div>
    </div>
  </header>
  <div id="contentWrapper">