<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <div class="left-ots">
        <h1 class="head">Вход</h1>
        <div class="box wide">
            <form class="" action="/login" method="post">
                <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="">
                </div>
                <div class="boxline">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="password" value="">
                </div>
                <div class="boxline">
                    <input type="checkbox" id="rememberme" name="rememberme" value="1">
                    <label class="form-check-label" for="rememberme">Запомнить меня</label>
                </div>
                <div class="row">
                    <div class="boxline">
                        <button type="submit" class="button-primary">Войти</button>
                        <small>
                            <span class="left-ots"><a href="/register">Регистрация</a></span>
                            <span class="left-ots"><a href="/recover">Забыли пароль?</a></span>
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
