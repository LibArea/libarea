<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
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
                <?php if ($GLOBALS['conf']['captcha']) { ?>
                    <div class="captcha_data">
                        <div class="captcha_wrap">
                            <div class="g-recaptcha" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?php echo $GLOBALS['conf']['public_key']; ?>"></div>
                            <script async defer nonce="" src="https://www.google.com/recaptcha/api.js?hl=ru"></script>
                        </div>
                    </div>   <br />
                <?php } ?>
                <div class="boxline">
                    <div class="boxline">
                        <button type="submit" class="button-primary">Регистрация</button>
                        <small>
                            <span class="left-ots"><a href="/login">Войти</a></span>
                        </small>
                    </div>

                </div>
            </form>
        </div>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>