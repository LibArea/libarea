<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">
        <h1>Настройка профиля</h3>
        <a class="right" href="/u/<?php echo $data['login']; ?>">Посмотреть профиль</a>
        
        <img alt="Профиль" src="/images/user/<?php echo $data['avatar']; ?>">
         
        <div class="box setting">
            <form action="/users/setting/edit" method="post" enctype="multipart/form-data">
            <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="name">Никнейм</label>
                    <?php echo $data['login']; ?>
                </div>
                <div class="boxline">
                    <label for="name">E-mail</label>
                    <?php echo $data['email']; ?>
                </div>
            
                <div class="boxline">
                    <label for="name">Имя</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?php echo $data['name']; ?>">
                </div>
               
                <div class="boxline">
                    <label for="about">О себе</label>
                    <textarea type="text" class="form-about" name="about" id="about"><?php echo $data['about']; ?></textarea>
                </div>
                <div class="boxline">
                    <input id="fileInput" name="image" accept="image/*" type="file" />
                </div>
                
                <div class="boxline">
                    <input type="hidden" name="nickname" id="nickname" value="">
                    <button type="submit" class="btn btn-primary">Изменить</button>
                </div>
                
            </form>
        </div>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>