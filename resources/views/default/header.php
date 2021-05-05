<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <?php if (isset($data['post']['post_is_delete'])) { ?>  
            <meta name="robots" content="noindex" />
        <?php } ?>

        <title><?= $data['title']; ?></title>
        <meta name = "description" content = "<?= $data['description']; ?>" />
 
        <meta property="og:title" content="<?= $data['title']; ?>">
        <meta property="og:description" content="<?= $data['description']; ?>"> 
        <meta property="og:site_name" content="<?= $GLOBALS['conf']['hometitle']; ?>">
        
        <?php if (isset($data['post']['post_id'])) { ?>
            <meta property="og:type" content="article">
            <meta property="og:url" content="//<?= HLEB_MAIN_DOMAIN; ?>/posts/<?=$data['post']['post_id']; ?>/<?=$data['post']['post_slug']; ?>">
            <meta property="article:published_time" content="<?=$data['post']['post_date']; ?>">
            <meta property="article:modified_time" content="<?=$data['post']['post_edit']; ?>">
            <link rel="canonical" href="//<?= HLEB_MAIN_DOMAIN; ?>/posts/<?=$data['post']['post_id']; ?>/<?=$data['post']['post_slug']; ?>">
        <?php } else { ?>
            <meta property="og:type" content="website">
            <meta property="og:image" content="//<?= HLEB_MAIN_DOMAIN; ?>/assets/images/areadev.jpg">
            <meta property="og:image:type" content="image/jpeg">
        <?php } ?>

        <link rel="icon" href="//<?= HLEB_MAIN_DOMAIN; ?>/favicon.ico">
        <link rel="apple-touch-icon" href="//<?= HLEB_MAIN_DOMAIN; ?>/favicon.png">

        <link rel="stylesheet" href="//<?= HLEB_MAIN_DOMAIN; ?>/assets/css/style.css">
        <?php if($uid['uri'] == '/flow') { ?>
            <script src="//<?= HLEB_MAIN_DOMAIN;  ?>/assets/js/flow.js"></script>
            <link rel="stylesheet" href="//<?= HLEB_MAIN_DOMAIN; ?>/assets/css/flow.css">
        <?php } ?>
    </head>
<body class="bd<?php if(Request::getCookie('dayNight') == 'dark') {?> dark<?php } ?><?php if(Request::getCookie('menuS') == 'menuno') {?> menuno<?php } ?>">
<header>  
<div class="wrap">

    <div class="menu-left"> 
        <ul>
            <li class="nav"> 
                <?php if($uid['uri'] == '/') { ?>
                   <a title="<?= lang('Home'); ?>" class="logo" href="//<?= HLEB_MAIN_DOMAIN; ?>">LoriUP</a>
                <?php } else { ?>
                    <a title="<?= lang('Home'); ?>" class="logo" href="//<?= HLEB_MAIN_DOMAIN; ?>"><i class="icon home"></i></a>
                    <span class="slash no-mob">\</span>
                    <a title="<?= lang('LoriUP'); ?>" class="home no-mob" href="//<?= HLEB_MAIN_DOMAIN; ?>"><?= lang('LoriUP'); ?></a>
                <?php } ?>
            </li> 

            <li class="nav no-mob closed-on">        
                <div class="togglemenu">
                    <i class="icon options-vertical"></i> 
                </div>
            </li>
        </ul>
    </div>
    <div class="menu-right">
        <div class="nav">
            <span id="toggledark" class="my-color-m">
                <i class="icon frame"></i> 
            </span>
        </div>
        <?php if(!$uid['id']) { ?>  
            <?php if($GLOBALS['conf']['invite'] != 1) { ?>
                <div class="nav">
                    <a class="register" title="<?= lang('Sign up'); ?>" href="//<?= HLEB_MAIN_DOMAIN; ?>/register">
                        <?= lang('Sign up'); ?>
                    </a>
                </div>
            <?php } ?>  
            <div class="nav">
                <a class="login" title="<?= lang('Sign in'); ?>" href="//<?= HLEB_MAIN_DOMAIN; ?>/login"><?= lang('Sign in'); ?></a>
            </div>                
        <?php } else { ?> 
            <div class="nav create">  
                <a href="//<?= HLEB_MAIN_DOMAIN; ?>/post/add"> 
                    <i class="icon pencil"></i>                    
                </a>
            </div>   
            <?php if($uid['notif']) { ?> 
                <div class="nav notif">  
                    <a href="//<?= HLEB_MAIN_DOMAIN; ?>/u/<?= $uid['login']; ?>/notifications"> 
                        <?php if($uid['notif']['action_type'] == 1) { ?>
                            <i class="icon envelope"></i>
                        <?php } else { ?>    
                            <i class="icon bell"></i>
                        <?php } ?>
                       
                    </a>
                </div>  
            <?php } ?>  
            <div class="dropbtn nav">
                <div class="b-my" title=""><span><?= $uid['login']; ?></span>  
                    <img class="ava" alt="<?= $uid['login']; ?>" src="//<?= HLEB_MAIN_DOMAIN; ?>/uploads/avatar/small/<?= $uid['avatar']; ?>">
                    <i class="icon arrow arrow-down"></i>
                </div>
                <div class="dropdown-menu">
                    <span class="st"></span>
                    <a href="//<?= HLEB_MAIN_DOMAIN; ?>/u/<?= $uid['login']; ?>">
                        <i class="icon user"></i>
                        <?= lang('Profile'); ?>
                    </a>
                    <a href="//<?= HLEB_MAIN_DOMAIN; ?>/u/<?= $uid['login']; ?>/setting">
                        <i class="icon settings"></i>
                        <?= lang('Settings'); ?>
                    </a>
                   <a href="//<?= HLEB_MAIN_DOMAIN; ?>/u/<?= $uid['login']; ?>/notifications">
                        <i class="icon bell"></i> 
                        <?= lang('Notifications'); ?>
                    </a>
                    <a href="//<?= HLEB_MAIN_DOMAIN; ?>/u/<?= $uid['login']; ?>/messages">
                        <i class="icon envelope"></i> 
                        <?= lang('Messages'); ?>
                    </a>
                    <a href="//<?= HLEB_MAIN_DOMAIN; ?>/u/<?= $uid['login']; ?>/comments"> 
                        <i class="icon bubbles"></i>
                        <?= lang('Comments'); ?> 
                    </a>
                    <a href="//<?= HLEB_MAIN_DOMAIN; ?>/u/<?= $uid['login']; ?>/favorite">
                        <i class="icon star"></i> 
                        <?= lang('Favorites'); ?>              
                    </a>
                    <?php if($uid['trust_level'] > 1) { ?>
                        <a href="//<?= HLEB_MAIN_DOMAIN; ?>/u/<?= $uid['login']; ?>/invitation">
                            <i class="icon link"></i>   
                            <?= lang('Invites'); ?>                   
                        </a> 
                    <?php } ?>  
                    <?php if($uid['trust_level'] == 5) { ?> 
                        <a href="//<?= HLEB_MAIN_DOMAIN; ?>/admin" target="_black">
                            <i class="icon shield"></i>    
                            <?= lang('Admin'); ?>                   
                        </a> 
                    <?php } ?>     
                    <hr>   
                    <a href="//<?= HLEB_MAIN_DOMAIN; ?>/logout" class="logout" target="_self" title="<?= lang('Sign out'); ?>">
                        <i class="icon logout"></i> 
                        <?= lang('Sign out'); ?>
                    </a>
                </div>
            </div>
        <?php } ?>     
    </div>
</div>    
</header>
<?php if(!empty($post['post_title'])) { ?>
    <div id="stHeader">
        <div class="wrap">
            <a href="//<?= HLEB_MAIN_DOMAIN; ?>"><i class="icon home"></i></a> <span class="slash">\</span> <?= $post['post_title']; ?>
        </div>
    </div>
<?php } ?>

<div class="wrap">
    <?php if ($uid['msg']) { ?>
        <?php foreach($uid['msg'] as $message){ ?>
            <?= $message; ?>
        <?php } ?>
    <?php } ?>