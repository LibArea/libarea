<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">  
  
<title><?= $data['meta_title']; ?></title>

<?php if (!empty($data['meta_desc'])) { ?>
  <meta name="description" content="<?= $data['meta_desc']; ?>">
<?php } ?>

<meta property="og:title" content="<?= $data['meta_title']; ?>"/>

<?php if (!empty($data['meta_desc'])) { ?>
  <meta property="og:description" content="<?= $data['meta_desc']; ?>"/>
<?php } ?>

<meta property="og:site_name" content="<?= Lori\Config::get(Lori\Config::PARAM_HOME_TITLE); ?>"/>

<?php if (!empty($data['meta_desc'])) { ?>
  <meta property="og:url" content="<?=  $data['canonical']; ?>"/>
<?php } ?>

<?php if ($data['sheet'] == 'article') {  ?>
  <meta property="og:type" content="article"/>
  <meta property="article:published_time" content="<?= $data['post_date']; ?>"/>
  <meta property="og:image" content="<?= $data['img']; ?>"/>
  <meta property="og:image:type" content="image/webp"/>
<?php } elseif ($data['sheet'] == 'profile') { ?> 
  <meta property="og:type" content="profile"/>
  <meta property="og:image" content="<?= $data['img']; ?>"/>
  <meta property="og:image:type" content="image/jpeg"/>  
<?php } elseif ($data['sheet'] == 'space') { ?> 
  <meta property="og:type" content="website"/>
  <meta property="og:image" content="<?= $data['img']; ?>"/>
  <meta property="og:image:type" content="image/jpeg"/>  
<?php } elseif ($data['sheet'] == 'feed') { ?> 
  <meta property="og:type" content="website"/>
  <meta property="og:image" content="<?= $data['img']; ?>"/>
  <meta property="og:image:type" content="image/webp"/>
<?php } else { ?>
  <meta property="og:type" content="website"/>
<?php } ?>

<?php if (!empty($data['canonical'])) {  ?>
  <link rel="canonical" href="<?= $data['canonical']; ?>">
<?php } ?>

<link rel="icon" sizes="16x16" href="/favicon-16.ico" type="image/x-icon">
<link rel="icon" sizes="48x48" href="/favicon-48.ico" type="image/x-icon">