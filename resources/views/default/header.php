<!DOCTYPE html>
<html lang="ru" prefix="og: http://ogp.me/ns# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
     
        <title><?= $data['meta_title']; ?></title>
        <meta name="description" content="<?= $data['meta_desc']; ?>">
 
        <?php getRequestHead()->output(); ?>
 
        <link rel="canonical" href="<?= $data['canonical']; ?>">
        
        <?php include TEMPLATE_DIR . '/OpenGraph.php'; ?>
        
        <link rel="icon" href="https://loriup.ru/favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon" href="https://loriup.ru/favicon.png">

        <script src="/assets/js/jquery.min.js"></script>
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    
<body class="bd<?php if(Request::getCookie('dayNight') == 'dark') {?> dark<?php } ?>">

<header>
    <div class="wrap">
        <div class="header-left"> 
            <a title="<?= lang('Home'); ?>" class="logo" href="/">LORI<span>UP</span></a>

            <form class="form" method="post" action="/search">
                <?= csrf_field() ?>
                <input type="text" name="q" id="search" placeholder="Найти..." class="form-search">
            </form>
           
           <a class="link no-mob" title="<?= lang('Spaces'); ?>" href="/space"><?= lang('Spaces'); ?></a> 
           <a class="link no-mob" title="<?= lang('Flow'); ?>" href="/flow"><?= lang('Flow'); ?></a>

        </div>
        <div class="header-right right">
 
            <?php if(!$uid['id']) { ?> 
                <?php if(!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
                    <div class="nav">
                        <a class="link register" title="<?= lang('Sign up'); ?>" href="/register">
                            <?= lang('Sign up'); ?>
                        </a>
                    </div>
                <?php } ?>  
                <div class="nav no-pc">
                    <a class="link login" title="<?= lang('Sign in'); ?>" href="/login"><?= lang('Sign in'); ?></a>
                </div> 
            <?php } else { ?> 
                <div class="dropbtn nav">
                    <div class="nick" title=""><span><?= $uid['login']; ?></span>  
                        <img class="ava" alt="<?= $uid['login']; ?>" src="<?= user_avatar_url($uid['avatar'], 'small'); ?>">
                        <i class="icon arrow arrow-down"></i>
                    </div>
                    <div class="dropdown-menu">
                        <span class="st"></span>
                        <a class="dr-menu" href="/u/<?= $uid['login']; ?>">
                            <i class="icon user"></i>
                            <?= lang('Profile'); ?>
                        </a>
                        <a class="dr-menu" href="/u/<?= $uid['login']; ?>/setting">
                            <i class="icon settings"></i>
                            <?= lang('Settings'); ?>
                        </a>
                        <a class="dr-menu" href="/u/<?= $uid['login']; ?>/drafts">
                            <i class="icon book-open"></i>
                            <?= lang('Drafts'); ?>
                        </a>
                        <a class="dr-menu" href="/u/<?= $uid['login']; ?>/notifications">
                            <i class="icon bell"></i> 
                            <?= lang('Notifications'); ?>
                        </a>
                        <a class="dr-menu" href="/u/<?= $uid['login']; ?>/messages">
                            <i class="icon envelope"></i> 
                            <?= lang('Messages-m'); ?>
                        </a>
                        <a class="dr-menu" href="/u/<?= $uid['login']; ?>/comments"> 
                            <i class="icon bubbles"></i>
                            <?= lang('Comments-n'); ?> 
                        </a>
                        <a class="dr-menu" href="/u/<?= $uid['login']; ?>/favorite">
                            <i class="icon star"></i> 
                            <?= lang('Favorites'); ?>              
                        </a>
                        <?php if($uid['trust_level'] > 1) { ?>
                            <a class="dr-menu" href="/u/<?= $uid['login']; ?>/invitation">
                                <i class="icon link"></i>   
                                <?= lang('Invites'); ?>                   
                            </a> 
                        <?php } ?>  
                        <?php if($uid['trust_level'] == 5) { ?> 
                            <a class="dr-menu" href="/admin" target="_black">
                                <i class="icon shield"></i>    
                                <?= lang('Admin'); ?>                   
                            </a> 
                        <?php } ?>     
                        <hr>   
                        <a class="dr-menu" href="/logout" class="logout" title="<?= lang('Sign out'); ?>">
                            <i class="icon logout"></i> 
                            <?= lang('Sign out'); ?>
                        </a>
                    </div>
                </div>
                <div class="nav create">  
                    <a class="add-post" href="/post/add"> 
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
            <?php } ?>  

            <div class="nav">
                <span id="toggledark" class="my-color-m">
                    <i class="icon frame"></i> 
                </span>
            </div>
        </div>
    </div>    
</header>

<?php if(!empty($space_bar)) { ?>
    <?php if(!$space_bar && $uid['uri'] == '/') { ?>
        <div class="space-no-user">
            <i class="icon diamond"></i>
            <?= lang('Read more'); ?>!  <a href="/space"><?= lang('Subscribe'); ?></a> <?= lang('on'); ?>
            <?= lang('interesting spaces'); ?>.
        </div>
    <?php }  ?>
<?php }  ?>