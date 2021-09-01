<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $meta['meta_title']; ?></title>

<?php if (!empty($meta['meta_desc'])) { ?>
  <meta name="description" content="<?= $meta['meta_desc']; ?>">
  <meta property="og:description" content="<?= $meta['meta_desc']; ?>" />
<?php } ?>

<meta property="og:title" content="<?= $meta['meta_title']; ?>" />
<meta property="og:site_name" content="<?= Lori\Config::get(Lori\Config::PARAM_HOME_TITLE); ?>" />

<?php if (!empty($meta['canonical'])) {  ?>
  <meta property="og:url" content="<?= $meta['canonical']; ?>" />
  <link rel="canonical" href="<?= $meta['canonical']; ?>">
<?php } ?>

<?php if (!empty($meta['sheet'])) {  ?>
    <?php if ($meta['sheet'] == 'article') {  ?>
      <meta property="og:type" content="article" />
      <meta property="article:published_time" content="<?= $meta['post_date']; ?>" />
      <meta property="og:image" content="<?= $meta['img']; ?>" />
      <meta property="og:image:type" content="image/webp" />
    <?php } elseif ($meta['sheet'] == 'profile') { ?>
      <meta property="og:type" content="profile" />
      <meta property="og:image" content="<?= $meta['img']; ?>" />
      <meta property="og:image:type" content="image/jpeg" />
    <?php } elseif ($meta['sheet'] == 'space' || $meta['sheet'] == 'topic') { ?>
      <meta property="og:type" content="website" />
      <meta property="og:image" content="<?= $meta['img']; ?>" />
      <meta property="og:image:type" content="image/jpeg" />
    <?php } elseif ($meta['sheet'] == 'feed') { ?>
      <meta property="og:type" content="website" />
      <meta property="og:image" content="<?= $meta['img']; ?>" />
      <meta property="og:image:type" content="image/webp" />
    <?php } else { ?>
      <meta property="og:type" content="website" />
    <?php } ?>
<?php } ?>

<link rel="icon" sizes="16x16" href="/favicon-16.ico" type="image/x-icon">
<link rel="icon" sizes="48x48" href="/favicon-48.ico" type="image/x-icon">