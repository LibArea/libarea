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
        
        <?php if(Request::getUri() == '/flow') { ?>
            <script src="/assets/js/flow.js"></script>
            <link rel="stylesheet" href="/assets/css/flow.css">
        <?php } ?>
    </head>
<body class="bd<?php if(Request::getCookie('dayNight') == 'dark') {?> dark<?php } ?><?php if(Request::getCookie('menuS') == 'menuno') {?> menuno<?php } ?>">
<header class="site-header">  
    <div class="title"> 
        <a title="<?= lang('Home'); ?>" class="logo" href="/">M</a>
        <div class="menu-left">
            <ul>
                <li class="nav">
                    <?php if(Request::getUri() == '/') { ?>
                        <a title="<?= lang('Home'); ?>" class="home" href="/"><?= lang('AreaDev'); ?></a>
                    <?php } else { ?>
                       <a title="<?= lang('Home'); ?>" class="home" href="/"><?= lang('Home'); ?></a>
                    <?php } ?>
                </li>
                <li class="nav no-mob closed-on">        
                    <div class="togglemenu">
                        <icon name="options-vertical"></icon> 
                    </div>
                </li>
                <?php if(!empty($space_info)) { ?>
                    <li class="nav no-mob"> 
                        <h1 class="space"> 
                            <div class="space-color space_<?= $space_info['space_color'] ?>"></div>
                            <a class="space-u" href="/s/<?= $space_info['space_slug']; ?>">
                              <?= $space_info['space_name']; ?>
                            </a>
                        </h1>
                    </li>
                <?php } ?> 
                
                <?php if(!empty($post['space_slug'])) { ?>
                    <li class="nav no-mob"> 
                        <div class="post-space-color">  
                            <div class="space-color space_<?= $post['space_color'] ?>"></div>
                            <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                               <?= $post['space_name']; ?>
                            </a>
                        </div>
                    </li>
                <?php } ?> 
            </ul>
        </div>
    </div>
    <div class="menu-h">
        <ul>
            <li class="nav">
                <span id="toggledark" class="my-color-m">
                    <icon name="frame"></icon> 
                </span>
            </li>
            <?php if(!$uid['id']) { ?>  
                <li class="nav">
                    <a class="login" title="<?= lang('Sign in'); ?>" href="/login"><?= lang('Sign in'); ?></a>
                </li>
                <?php if($GLOBALS['conf']['invite'] != 1) { ?>
                    <li class="nav">
                        <a class="register" title="<?= lang('Sign up'); ?>" href="/register">
                            <?= lang('Sign up'); ?>
                        </a>
                    </li>
                <?php } ?>    
            <?php } else { ?> 
                <li class="nav create">  
                    <a class="nav" href="/post/add"> 
                        <icon name="pencil"></icon>                    
                    </a>
                </li>   
                <?php if($uid['notif']) { ?> 
                    <li class="nav notif">  
                        <a class="nav" href="/u/<?= $uid['login']; ?>/notifications"> 
                            <?php if($uid['notif']['action_type'] == 1) { ?>
                                <icon name="envelope"></icon>
                            <?php } else { ?>    
                                <icon name="bell"></icon>
                            <?php } ?>
                           
                        </a>
                    </li>  
                <?php } ?>    
                <li class="dropbtn nav">
                    <div class="b-my" title=""><span><?= $uid['login']; ?></span>  
                        <img class="ava" alt="<?= $uid['login']; ?>" src="/uploads/avatar/small/<?= $uid['avatar']; ?>">
                        <icon class="arrow" name="arrow-down"></icon>
                    </div>
                    <span class="dropdown-menu">
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
                    </span>
                </li>
            <?php } ?>     
        </ul>    
    </div>
</header>

<?php if ($uid['msg']) { ?>
    <?php foreach($uid['msg'] as $message){ ?>
        <?= $message; ?>
    <?php } ?>
<?php } ?>