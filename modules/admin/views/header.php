<?php

/**
 * @var $container App\Bootstrap\ContainerInterface 
 */

$lang = Translate::getLang();

?>

<!DOCTYPE html>
<html lang="<?= $lang; ?>" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#" <?php if ($lang == 'ar') : ?> dir="rtl" <?php endif; ?>>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?= $meta; ?>

  <link rel="icon" sizes="16x16" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" sizes="120x120" href="/favicon-120.ico" type="image/x-icon">
  <link rel="icon" sizes="512x512" href="/favicon-512.png" type="image/png">

  <meta name="csrf-token" content="<?= csrf_token(); ?>">

  <link rel="stylesheet" href="/assets/css/style.css?<?= config('general', 'version'); ?>" type="text/css">
  <?php if ($lang == 'ar') : ?>
    <link rel="stylesheet" href="/assets/css/rtl.css" type="text/css">
  <?php endif; ?>
  <script src="/assets/js/la.js?<?= config('general', 'version'); ?>"></script>
</head>

<body class="admin<?php if ($container->cookies()->get('dayNight') == 'dark') : ?> dark<?php endif; ?>">

  <header class="d-header">
    <div class="wrap">
      <div class="d-header_contents">
        <svg class="icon menu__button none">
          <use xlink:href="/assets/svg/icons.svg#menu"></use>
        </svg>
        <div class="box-logo ml10 mr20">
          <a href="<?= url('admin'); ?>"><?= __('admin.home'); ?></a>
          <div class="gray-600">/</div>
          <a class="gray-600" href="/">
            <?= __('admin.website'); ?>
          </a>
        </div>
        <div class="flex gap-lg items-center w-90 ml20">
          <a class="<?= is_current(url('admin.users')) ? ' active' : ' gray-600'; ?>" href="<?= url('admin.users'); ?>">
            <svg class="icon mr5">
              <use xlink:href="/assets/svg/icons.svg#users"></use>
            </svg>
            <span class="mb-none middle text-sm"><?= __('admin.users'); ?></span>
          </a>
          <a class="<?= is_current(url('admin.facets.all')) ? ' active' : ' gray-600'; ?> mb-none" href="<?= url('admin.facets.all'); ?>">
            <svg class="icon mr5">
              <use xlink:href="/assets/svg/icons.svg#grid"></use>
            </svg>
            <span class="mb-none middle text-sm"><?= __('admin.facets'); ?></span>
          </a>
          <a class="<?= is_current(url('admin.tools')) ? ' active' : ' gray-600'; ?>" href="<?= url('admin.tools'); ?>">
            <svg class="icon mr5">
              <use xlink:href="/assets/svg/icons.svg#tool"></use>
            </svg>
            <span class="mb-none middle text-sm"><?= __('admin.tools'); ?></span>
          </a>
          <!-- Hide so as not to embarrass
          <a class="<?= is_current(url('admin.settings.general')) ? ' active' : ' gray-600'; ?> mb-none" href="<?= url('admin.settings.general'); ?>">
            <svg class="icon mr5">
              <use xlink:href="/assets/svg/icons.svg#settings"></use>
            </svg>
            <span class="mb-none middle text-sm"><?= __('admin.settings'); ?></span>
          </a> -->
        </div>
        <div class="mb-block">
          <span class="mb-none gray-600"><?= $container->request()->getUri()->getIp(); ?></span>
        </div>
      </div>
    </div>
  </header>
  <div id="contentWrapper" class="wrap">