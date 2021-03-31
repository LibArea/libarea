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

    </head>
<body class="bd<?php if(Request::getCookie('dayNight') == 'dark') {?> dark<?php } ?>">
<header>
	<div class="wrap">
		<div class="title">
            <a title="<?= lang('Home'); ?>" class="logo" href="/"><?= lang('My'); ?></a>
            <div class="menu-left">
                <ul>
                    <li class="nav no-mob">
                        <?php if(Request::getUri() == '/') { ?>
                            <a title="<?= lang('Home'); ?>" class="home" href="/"><?= lang('AreaDev'); ?></a>
                        <?php } else { ?>
                           <a title="<?= lang('Home'); ?>" class="home" href="/"><?= lang('Home'); ?></a>
                        <?php } ?>
                    </li>
                    <li class="nav no-mob<?php if(Request::getUri() == '/top') { ?> active<?php } ?>">
                        <a title="<?= lang('TOP'); ?>" class="home" href="/top">
                            <?= lang('TOP'); ?>
                        </a>
                    </li>
                    <li class="nav no-mob-two<?php if(Request::getUri() == '/comments') { ?> active<?php } ?>">
                        <a title="<?= lang('Comments'); ?>" class="comments" href="/comments">
                             <?= lang('Comments'); ?>
                        </a>
                    </li>   
                    <li class="nav no-mob-two<?php if(Request::getUri() == '/space') { ?> active<?php } ?>">
                        <a title="<?= lang('Space'); ?>" class="sp" href="/space">~</a>
                    </li>
                </ul>
            </div>
		</div>
		<div class="menu">
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
                    <li class="nav">
                        <a class="register" title="<?= lang('Sign up'); ?>" href="/register"><?= lang('Sign up'); ?></a>
                    </li>
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
