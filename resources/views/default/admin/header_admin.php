<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title><?= $data['title']; ?></title>
        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/assets/css/admin.css">
        <link rel="icon" href="/favicon.ico">
        <?php if($uid['id']) { ?>
            <script src="/assets/js/admin.js"></script>
        <?php } ?> 
    </head>
<body class="bd">
<header class="site-header">
    <div class="title">
        <a title="<?= lang('Home'); ?>" class="logo" href="/">M</a>
        <div class="menu-left">
            <ul>
                <li class="nav<?php if( $uid['uri'] == '/admin') { ?> active<?php } ?>">
                    <a class="home" title="<?= lang('Admin'); ?>" href="/admin">
                        <?= lang('Admin'); ?>
                    </a>
                </li>
                <li class="nav<?php if( $uid['uri'] == '/admin/space') { ?> active<?php } ?>">
                    <a class="home" title="<?= lang('Space'); ?>" href="/admin/space">
                        <?= lang('Space'); ?>
                    </a>
                </li>
                <li class="nav<?php if( $uid['uri'] == '/admin/invitations') { ?> active<?php } ?>"> 
                    <a class="home" title="<?= lang('Invites'); ?>" href="/admin/invitations">
                        <?= lang('Invites'); ?>
                    </a>
                </li>
                <li class="nav<?php if( $uid['uri'] == '/admin/comments') { ?> active<?php } ?>">
                    <a class="home" title="<?= lang('Comments'); ?>" href="/admin/comments">
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
                            <i class="icon bell"></i> 
                        </a>
                    </li>  
                <?php } ?>    
                <li class="dropbtn nav">
                    <a class="b-my" href="/u/<?= $uid['login']; ?>" title="<?= $uid['login']; ?>">
                        <span><?= $uid['login']; ?></span>  
                    </a>
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