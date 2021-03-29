<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">
        <div class="left-ots">
            <h1 class="head">Регистрация</h1>
            <div class="box wide">
                <form class="" action="/register/add" method="post">
                    <?php csrf_field(); ?>
                    <div class="boxline">
                        <label for="login">Никнейм</label>
                        <input type="text" name="login" id="login">
             
                    </div>
                    <div class="boxline">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email">
                    </div>
                    <div class="boxline">
                        <label for="password">Пароль</label>
                        <input type="password" name="password" id="password">
                    </div>
                     <div class="boxline">
                        <label for="password_confirm">Повторите пароль</label>
                        <input type="password" name="password_confirm" id="password_confirm">
                    </div>                    
                    <div class="boxline">
                        <div class="boxline">
                            <button type="submit" class="button-primary">Регистрация</button>
                        </div>
                        <div class="boxline">
                            <a href="/login">Войти</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>