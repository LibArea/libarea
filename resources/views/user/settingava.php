<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">
        <h1>Загрузка аватарки</h3>
        <a class="right" href="/u/<?php echo $data['login']; ?>">Посмотреть профиль</a>
        
        <img alt="Профиль" src="/images/user/<?php echo $data['avatar']; ?>">
         
        <div class="box setting">
               
        </div>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>