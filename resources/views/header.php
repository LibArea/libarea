<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <?php if (isset($data['post']['is_delete'])) { ?>  
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
            <a title="На главную" class="logo" href="/">My</a>
            <div class="menu-left">
                <ul>
                    <li class="nav no-mob">
                        <?php if(Request::getUri() == '/') { ?>
                            <a title="На главную" class="home" href="/">AreaDev</a>
                        <?php } else { ?>
                           <a title="На главную" class="home" href="/">Главная</a>
                        <?php } ?>
                    </li>
                    <li class="nav no-mob">
                        <a title="TOP постов" class="home" href="/top">TOP</a>
                    </li>
                    <li class="nav no-mob-two">
                        <a title="Все комментарии" class="comments" href="/comments">Комментарии</a>
                    </li>  
                    <li class="nav no-mob-two">
                        <a title="Пространства" class="sp" href="/space">∞</a>
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
                        <a class="login" href="/login">Войти</a>
                    </li>
                    <li class="nav">
                        <a class="register" href="/register">Регистрация</a>
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
                                Профиль
                            </a>
                            <a href="/users/setting">
                                <svg class="md-icon">
                                    <use xlink:href="/assets/svg/icons.svg#settings"></use>
                                </svg> 
                                Настройки
                            </a>
                            <a href="/threads/<?= $uid['login']; ?>"> 
                                <svg class="md-icon">
                                    <use xlink:href="/assets/svg/icons.svg#devices"></use>
                                </svg> 
                                Мои ответы 
                            </a>
                            <a href="/favorite/<?= $uid['login']; ?>">
                                <svg class="md-icon">
                                    <use xlink:href="/assets/svg/icons.svg#bookmark"></use>
                                </svg>  
                                Избранное                   
                            </a>
                            <?php if($uid['trust_level'] == 5) { ?>
                                <a href="/admin" target="_black">
                                    <svg class="md-icon">
                                        <use xlink:href="/assets/svg/icons.svg#ad"></use>
                                    </svg>   
                                    Админка                    
                                </a> 
                            <?php } ?>     
                            <hr>   
                            <a href="/logout" class="logout" target="_self" title="Выход">
                                <svg class="md-icon">
                                    <use xlink:href="/assets/svg/icons.svg#arrow-bar-to-right"></use>
                                </svg> 
                                Выход
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
