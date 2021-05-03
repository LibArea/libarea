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
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/favicon.png">

        <?php if($uid['uri'] == '/flow') { ?>
            <script src="/assets/js/flow.js"></script>
            <link rel="stylesheet" href="/assets/css/flow.css">
        <?php } ?>
    </head>
<body class="bd<?php if(Request::getCookie('dayNight') == 'dark') {?> dark<?php } ?><?php if(Request::getCookie('menuS') == 'menuno') {?> menuno<?php } ?>">
<header>  
<div class="wrap">
    <div class="menu-left"> 
        <ul>
            <li class="nav"> 
                <?php if($uid['uri'] == '/') { ?>
                   <a title="<?= lang('Home'); ?>" class="logo" href="/">LoriUP</a>
                <?php } else { ?>
                    <a title="<?= lang('Home'); ?>" class="logo" href="/"><icon name="home"></icon></a></a> 
                    <span class="slash">\</span>
                    <a title="<?= lang('LoriUP'); ?>" class="home" href="/"><?= lang('LoriUP'); ?></a>
                <?php } ?>
            </li> 

            <li class="nav no-mob closed-on">        
                <div class="togglemenu">
                    <icon name="options-vertical"></icon> 
                </div>
            </li>
        </ul>
    </div>
    <div class="menu-right">
        <div class="nav">
            <span id="toggledark" class="my-color-m">
                <icon name="frame"></icon> 
            </span>
        </div>
        <?php if(!$uid['id']) { ?>  
            <?php if($GLOBALS['conf']['invite'] != 1) { ?>
                <div class="nav">
                    <a class="register" title="<?= lang('Sign up'); ?>" href="/register">
                        <?= lang('Sign up'); ?>
                    </a>
                </div>
            <?php } ?>  
            <div class="nav">
                <a class="login" title="<?= lang('Sign in'); ?>" href="/login"><?= lang('Sign in'); ?></a>
            </div>                
        <?php } else { ?> 
            <div class="nav create">  
                <a href="/post/add"> 
                    <icon name="pencil"></icon>                    
                </a>
            </div>   
            <?php if($uid['notif']) { ?> 
                <div class="nav notif">  
                    <a href="/u/<?= $uid['login']; ?>/notifications"> 
                        <?php if($uid['notif']['action_type'] == 1) { ?>
                            <icon name="envelope"></icon>
                        <?php } else { ?>    
                            <icon name="bell"></icon>
                        <?php } ?>
                       
                    </a>
                </div>  
            <?php } ?>  
            <div class="dropbtn nav">
                <div class="b-my" title=""><span><?= $uid['login']; ?></span>  
                    <img class="ava" alt="<?= $uid['login']; ?>" src="/uploads/avatar/small/<?= $uid['avatar']; ?>">
                    <icon class="arrow" name="arrow-down"></icon>
                </div>
                <div class="dropdown-menu">
                    <span class="st"></span>
                    <a href="/u/<?= $uid['login']; ?>">
                        <icon name="user"></icon>
                        <?= lang('Profile'); ?>
                    </a>
                    <a href="/u/<?= $uid['login']; ?>/setting">
                        <icon name="settings"></icon>
                        <?= lang('Settings'); ?>
                    </a>
                   <a href="/u/<?= $uid['login']; ?>/notifications">
                        <icon name="bell"></icon> 
                        <?= lang('Notifications'); ?>
                    </a>
                    <a href="/u/<?= $uid['login']; ?>/messages">
                        <icon name="envelope"></icon> 
                        <?= lang('Messages'); ?>
                    </a>
                    <a href="/u/<?= $uid['login']; ?>/comments"> 
                        <icon name="bubbles"></icon>
                        <?= lang('Comments'); ?> 
                    </a>
                    <a href="/u/<?= $uid['login']; ?>/favorite">
                        <icon name="star"></icon> 
                        <?= lang('Favorites'); ?>              
                    </a>
                    <?php if($uid['trust_level'] > 1) { ?>
                        <a href="/u/<?= $uid['login']; ?>/invitation">
                            <icon name="link"></icon>   
                            <?= lang('Invites'); ?>                   
                        </a> 
                    <?php } ?>  
                    <?php if($uid['trust_level'] == 5) { ?> 
                        <a href="/admin" target="_black">
                            <icon name="shield"></icon>    
                            <?= lang('Admin'); ?>                   
                        </a> 
                    <?php } ?>     
                    <hr>   
                    <a href="/logout" class="logout" target="_self" title="<?= lang('Sign out'); ?>">
                        <icon name="logout"></icon> 
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
            <a href="/"><icon name="home"></icon></a> <span class="slash">\</span> <?= $post['post_title']; ?>
        </div>
    </div>
<?php } ?>

<div class="wrap">
    <?php if ($uid['msg']) { ?>
        <?php foreach($uid['msg'] as $message){ ?>
            <?= $message; ?>
        <?php } ?>
    <?php } ?>