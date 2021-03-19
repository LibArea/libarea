<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title><?php echo $data['title']; ?></title>

        <link rel="stylesheet" href="/css/style.css">
        <script src="/js/jquery.min.js"></script>
        <script src="/js/common.js"></script>
       
       <link rel="icon" href="/favicon.ico">

        <?php if(!empty($data['uid']['id'])) { ?>
            <script src="/js/app.js"></script>
        <?php } ?> 
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
                            <use xlink:href="/svg/icons.svg#moon"></use>
                        </svg>
                    </span>
                </li>
                <?php if(!empty($data['uid']['id'])) { ?> 
                    <li class="nav create">  
                        <a class="nav" href="/post/add">  
                            <svg class="md-icon">
                                <use xlink:href="/svg/icons.svg#plus"></use>
                            </svg> 
                        </a>
                    </li>   
                    <?php if($data['uid']['notif']) { ?> 
                        <li class="nav notif">  
                            <a class="nav" href="/notifications">  
                                <svg class="md-icon">
                                    <use xlink:href="/svg/icons.svg#mail"></use>
                                </svg> 
                            </a>
                        </li>  
                    <?php } ?>    
                    <li class="dropbtn nav">
                        <a class="b-my" href="#" title=""><span><?= $data['uid']['login']; ?></span>  
                            <svg class="md-icon">
                                <use xlink:href="/svg/icons.svg#chevrons-down"></use>
                            </svg>  
                        </a>
                        <span class="dropdown-menu">
                            <span class="st"></span>
                            <a href="/u/<?= $data['uid']['login']; ?>">
                                <svg class="md-icon">
                                    <use xlink:href="/svg/icons.svg#user"></use>
                                </svg> 
                                Профиль
                            </a>
                            <a href="/users/setting">
                                <svg class="md-icon">
                                    <use xlink:href="/svg/icons.svg#settings"></use>
                                </svg> 
                                Настройки
                            </a>
                            <a href="/threads/<?= $data['uid']['login']; ?>"> 
                                <svg class="md-icon">
                                    <use xlink:href="/svg/icons.svg#devices"></use>
                                </svg> 
                                Мои ответы 
                            </a>
                            <a href="/admin" target="_black">
                                <svg class="md-icon">
                                    <use xlink:href="/svg/icons.svg#ad"></use>
                                </svg>   
                                Админка                    
                            </a>
                            <hr>   
                            <a href="/logout" class="logout" target="_self" title="Выход">
                                <svg class="md-icon">
                                    <use xlink:href="/svg/icons.svg#arrow-bar-to-right"></use>
                                </svg> 
                                Выход
                            </a>
                        </span>
                    </li>
                <?php } else { ?> 
                    <li class="nav">
                        <a class="login" href="/login">Войти</a>
                    </li>
                    <li class="nav">
                        <a class="register" href="/register">Регистрация</a>
                    </li>
                <?php } ?>     
            </ul>    
		</div>
	</div>
</header>

    <?php if ($data['msg']) { ?>
      <div class="wrap">
        <div class="msg">
          <?php foreach($data['msg'] as $message){ ?>
            <?php echo $message; ?>
          <?php } ?>
        </div>
      </div>
    <?php } ?>
