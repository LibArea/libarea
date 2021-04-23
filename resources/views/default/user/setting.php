<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100 max-width">
     
    <ul class="nav-tabs">
        <li class="active"><span>Настройка профиля</span></li>
        <li><a href="/u/<?= $uid['login']; ?>/setting/avatar"><span>Аватар</span></a></li>
        <li><a href="/u/<?= $uid['login']; ?>/setting/security"><span>Пароль</span></a></li>
    </ul>

    <div class="box setting">
        <form action="/users/setting/edit" method="post" enctype="multipart/form-data">
        <?php csrf_field(); ?>
            <div class="boxline">
                <label for="name">Никнейм</label>
                <img class="mini ava" src="/uploads/avatar/small/<?php echo $user['avatar']; ?>"> 
                <?php echo $user['login']; ?>
            </div>
            <div class="boxline">
                <label for="name">E-mail</label>
                <?php echo $user['email']; ?>
            </div>
        
            <div class="boxline">
                <label for="name">Имя</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo $user['name']; ?>">
            </div>
           
            <div class="boxline">
                <label for="about">О себе</label>
                <textarea type="text" rows="4" class="form-about" name="about" id="about"><?php echo $user['about']; ?></textarea>
            </div>
           
            <div class="boxline">
                <input type="hidden" name="nickname" id="nickname" value="">
                <button type="submit" class="btn btn-primary">Изменить</button>
            </div>
            
        </form>
    </div>
</main>
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>