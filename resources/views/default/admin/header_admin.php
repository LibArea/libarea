<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title><?= $data['title']; ?></title>
        <meta name = "description" content = "<?= $data['description']; ?>" />
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/assets/css/admin.css">
        <link rel="icon" href="/favicon.ico">
        <?php if($uid['id']) { ?>
            <script src="/assets/js/jquery.min.js"></script>
            <script src="/assets/js/common.js"></script>
            <script src="/assets/js/app.js"></script>
            <script src="/assets/js/admin.js"></script>
        <?php } ?> 
    </head>
<body class="bd">
<header>
    <div class="title">
        <a title="<?= lang('Home'); ?>" class="logo" href="/">M</a>
        <div class="menu-left">
            <ul>
                <li class="nav no-mob">
                    <a title="<?= lang('Admin'); ?>" class="home" href="/admin"><?= lang('Admin'); ?></a>
                </li>
                <li class="nav no-mob">
                    <a title="<?= lang('Space'); ?>" class="home" href="/admin/space">
                        <?= lang('Space'); ?>
                    </a>
                </li>
                <li class="nav no-mob"> 
                    <a title="<?= lang('Invites'); ?>" class="home" href="/admin/invitations">
                        <?= lang('Invites'); ?>
                    </a>
                </li>
                <li class="nav no-mob">
                    <a title="<?= lang('Comments'); ?>" class="home" href="/admin/comments">
                        <?= lang('Comments'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="menu-h">
        <ul>

            <?php if(!$uid['id']) { ?> 
                <li class="nav">
                    <a class="login" href="/login">Войти</a>
                </li>
                <li class="nav">
                    <a class="register" href="/register">Регистрация</a>
                </li>
            <?php } else { ?> 
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
                        <a href="/users/invitation">
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#link"></use>
                            </svg>   
                            <?= lang('Invites'); ?>                   
                        </a> 
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
    <div class="wrap">
        <div class="msg">
            <?php foreach($uid['msg'] as $message){ ?>
                <?= $message; ?>
            <?php } ?>
        </div>
    </div>
<?php } ?>
