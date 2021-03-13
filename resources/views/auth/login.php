<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h1 class="head">Вход</h1>
        <div class="box wide">
            <form class="" action="/login" method="post">
                <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="ss@sdf.ru">
                </div>
                <div class="boxline">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="password" value="">
                    <small>qwer14qwer14</small>
                </div>

                <div class="boxline">
                    <input type="checkbox" id="rememberme" name="rememberme" value="1">
                    <label class="form-check-label" for="rememberme">Запомнить меня</label>
                </div>

                <div class="row">
                    <div class="boxline">
                        <button type="submit" class="button-primary">Войти</button>
                    </div>
                    <div class="boxline">
                        <a href="/register">Регистрация</a> &emsp;
                        <a href="/">Забыли пароль?</a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
