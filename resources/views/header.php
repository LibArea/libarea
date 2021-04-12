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
        <script src="/assets/js/jquery.min.js"></script>
        <link rel="icon" href="/favicon.ico">
     
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
                    <div id="togglemenu">
                        <svg class="md-icon moon">
                            <use xlink:href="/assets/svg/icons.svg#arrow-bar-to-left"></use>
                        </svg>
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
                        <span class="post-space-color">  
                            <div class="space-color space_<?= $post['space_color'] ?>"></div>
                            <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                               <?= $post['space_name']; ?>
                            </a>
                        </span>
                    </li>
                <?php } ?> 
            </ul>
        </div>
    </div>
    <div class="menu-h">
        <ul>
            <li class="nav">
                <span id="toggledark" class="my-color-m">
                    <svg class="md-icon moon">
                        <use xlink:href="/assets/svg/icons.svg#moon"></use>
                    </svg>
                </span>
            </li>
            <?php if(!$uid['id']) { ?>  
                <li class="nav">
                    <a class="login" title="<?= lang('Sign in'); ?>" href="/login"><?= lang('Sign in'); ?></a>
                </li>
                <?php if($GLOBALS['conf']['invite'] != 1) { ?>
                    <li class="nav">
                        <a class="register" title="<?= lang('Sign up'); ?>" href="/register"><?= lang('Sign up'); ?></a>
                    </li>
                <?php } ?>    
            <?php } else { ?> 
                <li class="nav create">  
                    <a class="nav" href="/post/add">  
                        <svg class="md-icon">
                            <use xlink:href="/assets/svg/icons.svg#plus"></use>
                        </svg> 
                    </a>
                </li>   
                <?php if($uid['notif']) { ?> 
                    <li class="nav notif">  
                        <a class="nav" href="/notifications">  
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#mail"></use>
                            </svg> 
                        </a>
                    </li>  
                <?php } ?>    
                <li class="dropbtn nav">
                    <a class="b-my" href="#" title=""><span><?= $uid['login']; ?></span>  
                        <img class="ava" alt="<?= $uid['login']; ?>" src="/uploads/avatar/small/<?= $uid['avatar']; ?>">
                        <svg class="md-icon">
                            <use xlink:href="/assets/svg/icons.svg#chevrons-down"></use>
                        </svg>  
                    </a>
                    <span class="dropdown-menu">
                        <span class="st"></span>
                        <a href="/u/<?= $uid['login']; ?>">
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#user"></use>
                            </svg> 
                            <?= lang('Profile'); ?>
                        </a>
                        <a href="/users/setting">
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#settings"></use>
                            </svg> 
                            <?= lang('Settings'); ?>
                        </a>
                        <a href="/messages">
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#mail"></use>
                            </svg>  
                            <?= lang('Messages'); ?>
                        </a>
                        <a href="/threads/<?= $uid['login']; ?>"> 
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#message"></use>
                            </svg> 
                            <?= lang('Comments'); ?> 
                        </a>
                        <a href="/favorite/<?= $uid['login']; ?>">
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#bookmark"></use>
                            </svg>  
                            <?= lang('Favorites'); ?>              
                        </a>
                        <?php if($uid['trust_level'] > 1) { ?>
                            <a href="/users/invitation">
                                <svg class="md-icon">
                                    <use xlink:href="/assets/svg/icons.svg#link"></use>
                                </svg>   
                                <?= lang('Invites'); ?>                   
                            </a> 
                        <?php } ?>  
                        <?php if($uid['trust_level'] == 5) { ?> 
                            <a href="/admin" target="_black">
                                <svg class="md-icon">
                                    <use xlink:href="/assets/svg/icons.svg#ad"></use>
                                </svg>   
                                <?= lang('Admin'); ?>                   
                            </a> 
                        <?php } ?>     
                        <hr>   
                        <a href="/logout" class="logout" target="_self" title="<?= lang('Sign out'); ?>">
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#arrow-bar-to-right"></use>
                            </svg> 
                            <?= lang('Sign out'); ?>
                        </a>
                    </span>
                </li>
            <?php } ?>     
        </ul>    
    </div>
</header>

<?php if ($uid['msg']) { ?>

    <div class="msg">
        <?php foreach($uid['msg'] as $message){ ?>
            <?= $message; ?>
        <?php } ?>
    </div>

<?php } ?>
