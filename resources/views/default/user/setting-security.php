<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <a class="right" href="/u/<?= $uid['login']; ?>">Посмотреть профиль</a>
    <ul class="nav-tabs">
        <li><a href="/users/setting"><span>Настройка профиля</span></a></li>
        <li><a href="/users/setting/avatar"><span>Аватар</span></a></li>
        <li class="active"><span>Пароль</span></li>
    </ul>
    <div class="box setting">
           <form action="/users/setting/security/edit" method="post" enctype="multipart/form-data">
            <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="name">Старый</label>
                    <input type="text" class="form-control" name="password" id="password" value="<?= $data['password']; ?>">
                </div>
                <div class="boxline">
                    <label for="name">Новый</label>
                    <input type="text" class="form-control" name="password2" id="password2" value="<?= $data['password2']; ?>">
                </div>
                <div class="boxline">
                    <label for="name">Повторите</label>
                    <input type="text" class="form-control" name="password3" id="password3" value="<?= $data['password3']; ?>">
                </div>
                <div class="boxline">
                    <input type="hidden" name="nickname" id="nickname" value="">
                    <button type="submit" class="btn btn-primary">Изменить</button>
                </div>
                
            </form>    
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>