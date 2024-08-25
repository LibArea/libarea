<?php $lang = Translate::getLang(); ?>

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
  <link rel="manifest" href="/manifest.json">
  <link rel="search" type="application/opensearchdescription+xml" href="<?= url('opensearch'); ?>" title="<?= __('app.search'); ?>">
  
  <meta name="csrf-token" content="<?= csrf_token(); ?>">
  
  <link rel="stylesheet" href="/assets/css/style.css?<?= config('general', 'version'); ?>" type="text/css">
  <link rel="stylesheet" href="/assets/css/minimum.css?<?= config('general', 'version'); ?>" type="text/css">

  <?php if ($lang == 'ar') : ?>
    <link rel="stylesheet" href="/assets/css/rtl.css" type="text/css">
  <?php endif; ?>
  <script src="/assets/js/la.js"></script>
  <script src="/assets/js/prism/prism.js"></script>
</head>