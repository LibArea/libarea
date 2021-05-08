<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
     
        <?php getRequestHead()->output(); ?>
 
        <?php if (isset($post['post_is_delete'])) { ?>  
            <meta name="robots" content="noindex" />
        <?php } ?>

        <link rel="canonical" href="<?= $data['canonical']; ?>">
        
        <link rel="icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/favicon.png">

        <link rel="stylesheet" href="/assets/css/style.css">
          
    </head>
<body class="bd<?php if(Request::getCookie('dayNight') == 'dark') {?> dark<?php } ?><?php if(Request::getCookie('menuS') == 'menuno') {?> menuno<?php } ?>">
<header<?php if(!isset($space_info['space_id']) !=1) {?> class="space"<?php } ?>>
<div class="wrap">

    <div class="menu-left"> 
        <ul>
            <li class="nav"> 
                <?php if($uid['uri'] == '/') { ?>
                   <a title="<?= lang('Home'); ?>" class="logo" href="/">LoriUP</a>
                <?php } else { ?>
                    <a title="<?= lang('Home'); ?>" class="logo" href="/"><i class="icon home"></i> 
                        <span class="slash no-mob">
                            <span class="sl">\</span> <?= lang('LoriUP'); ?>
                        </span>
                    </a>
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
            <?php if(!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
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
                    <i class="icon pencil"></i>                    
                </a>
            </div>   
            <?php if($uid['notif']) { ?> 
                <div class="nav notif">  
                    <a href="/u/<?= $uid['login']; ?>/notifications"> 
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
                    <img class="ava" alt="<?= $uid['login']; ?>" src="/uploads/avatar/small/<?= $uid['avatar']; ?>">
                    <i class="icon arrow arrow-down"></i>
                </div>
                <div class="dropdown-menu">
                    <span class="st"></span>
                    <a href="/u/<?= $uid['login']; ?>">
                        <i class="icon user"></i>
                        <?= lang('Profile'); ?>
                    </a>
                    <a href="/u/<?= $uid['login']; ?>/setting">
                        <i class="icon settings"></i>
                        <?= lang('Settings'); ?>
                    </a>
                   <a href="/u/<?= $uid['login']; ?>/notifications">
                        <i class="icon bell"></i> 
                        <?= lang('Notifications'); ?>
                    </a>
                    <a href="/u/<?= $uid['login']; ?>/messages">
                        <i class="icon envelope"></i> 
                        <?= lang('Messages'); ?>
                    </a>
                    <a href="/u/<?= $uid['login']; ?>/comments"> 
                        <i class="icon bubbles"></i>
                        <?= lang('Comments'); ?> 
                    </a>
                    <a href="/u/<?= $uid['login']; ?>/favorite">
                        <i class="icon star"></i> 
                        <?= lang('Favorites'); ?>              
                    </a>
                    <?php if($uid['trust_level'] > 1) { ?>
                        <a href="/u/<?= $uid['login']; ?>/invitation">
                            <i class="icon link"></i>   
                            <?= lang('Invites'); ?>                   
                        </a> 
                    <?php } ?>  
                    <?php if($uid['trust_level'] == 5) { ?> 
                        <a href="/admin" target="_black">
                            <i class="icon shield"></i>    
                            <?= lang('Admin'); ?>                   
                        </a> 
                    <?php } ?>     
                    <hr>   
                    <a href="/logout" class="logout" target="_self" title="<?= lang('Sign out'); ?>">
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
            <a href="/"><i class="icon home"></i></a> <span class="slash">\</span> <?= $post['post_title']; ?>
        </div>
    </div>
<?php } ?>

<div class="wrap">
    <?php if ($uid['msg']) { ?>
        <?php foreach($uid['msg'] as $message){ ?>
            <?= $message; ?>
        <?php } ?>
    <?php } ?>