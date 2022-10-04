<?php $lang = Translate::getLang(); ?>

<!DOCTYPE html>
<html lang="<?= $lang; ?>" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?= $meta; ?>
  <?php getRequestHead()->output(); ?>
  <link rel="icon" sizes="16x16" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" sizes="120x120" href="/favicon-120.ico" type="image/x-icon">
  <meta name="csrf-token" content="<?= csrf_token(); ?>">
  
  <?php if($lang == 'ar') : ?>
    <link rel="stylesheet" href="/assets/css/rtl.css" type="text/css">
  <?php endif; ?>
</head>