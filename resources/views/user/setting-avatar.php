<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <div class="left-ots">
        <a class="right" href="/u/<?php echo $uid['login']; ?>">Посмотреть профиль</a>
        <ul class="nav-tabs">
            <li><a href="/users/setting"><span>Настройка профиля</span></a></li>
            <li class="active"><span>Аватар</span></li>
            <li><a href="/users/setting/security"><span>Пароль</span></a></li>
        </ul>
        <div class="box setting"> 
           <img width="110" height="110" src="/uploads/avatar/<?= $uid['avatar']; ?>">
           
           <form method="POST" action="/users/setting/avatar/edit" enctype="multipart/form-data">
           <?= csrf_field() ?>
              <input type="file" name="image" accept="image/*"/>
              <p>Выберите файл для загрузки 120x120px (jpg, jpeg, png)</p>
              <p><input type="submit" value="Загрузить"/></p>
            </form>
        </div> 
    </div>            
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>