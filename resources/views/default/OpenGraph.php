<meta property="og:title" content="<?= $data['meta_title']; ?>"/>
<meta property="og:description" content="<?= $data['meta_desc']; ?>"/>
<meta property="og:site_name" content="<?= Lori\Config::get(Lori\Config::PARAM_HOME_TITLE); ?>"/>
<meta property="og:url" content="<?=  $data['canonical']; ?>"/>

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