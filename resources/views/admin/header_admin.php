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
        <a title="На главную" class="logo" href="/">My</a>
        <div class="menu-left">
            <ul>
                <li class="nav no-mob">
                    <a title="Админка" class="home" href="/admin">Админка</a>
                </li>
                <li class="nav no-mob">
                    <a title="Участники" class="home" href="#">
                        <svg class="md-icon">
                            <use xlink:href="/assets/svg/icons.svg#user"></use>
                        </svg> 
                        Участники
                    </a>
                </li>
                <li class="nav no-mob"> 
                    <a title="Посты" class="home" href="#">
                        <svg class="md-icon">
                            <use xlink:href="/assets/svg/icons.svg#devices"></use>
                        </svg> 
                        Посты
                    </a>
                </li>
                <li class="nav no-mob">
                    <a title="Комментарии" class="home" href="#">
                        <svg class="md-icon">
                            <use xlink:href="/assets/svg/icons.svg#message"></use>
                        </svg> 
                        Комментарии
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="menu">
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
                        <a href="/admin" target="_black">
                            <svg class="md-icon">
                                <use xlink:href="/assets/svg/icons.svg#ad"></use>
                            </svg>   
                            Админка                    
                        </a>
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
