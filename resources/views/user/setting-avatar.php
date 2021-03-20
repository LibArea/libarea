<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">
        <a class="right" href="/u/<?php echo $data['login']; ?>">Посмотреть профиль</a>
        <ul class="nav-tabs">
            <li><a href="/users/setting"><span>Настройка профиля</span></a></li>
            <li class="active"><span>Аватар</span></li>
            <li><a href="/users/setting/security"><span>Пароль</span></a></li>
        </ul>
        <div class="box setting"> <!-- /users/setting/avatar/edit -->
               В стадии разработки....
        </div>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>