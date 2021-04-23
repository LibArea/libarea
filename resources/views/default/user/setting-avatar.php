<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100 max-width">
    <ul class="nav-tabs">
        <li><a href="/u/<?= $uid['login']; ?>/setting"><span>Настройка профиля</span></a></li>
        <li class="active"><span>Аватар</span></li>
        <li><a href="/u/<?= $uid['login']; ?>/setting/security"><span>Пароль</span></a></li>
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
</main>
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>