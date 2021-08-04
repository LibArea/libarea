<div class="login-nav-home white-box">
    <div class="pt5 pr15 pb5 pl15 big">
        <form class="" action="/login" method="post">
            <?php csrf_field(); ?>
            <div class="login-nav">
                <label for="email">Email</label>
                <input type="email" placeholder="<?= lang('Enter'); ?>  e-mail" name="email" id="email">
            </div>
            <div class="login-nav">
                <label for="password"><?= lang('Password'); ?></label>
                <input type="password" placeholder="<?= lang('Enter your password'); ?>" name="password" id="password">
            </div>
            <div class="login-nav">
                <input type="checkbox" id="rememberme" name="rememberme" value="1">
                <label id="rem-text" class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
            </div>
            <div class="login-nav">
                <button type="submit" class="button-primary"><?= lang('Sign in'); ?></button>
            </div>
            <div class="login-nav center size-13">
                Продолжая, вы соглашаетесь с <a href="/info/privacy">Условиями использования</a> сайта
            </div>
            <div class="login-nav center size-13">
                <a class="recover" href="/recover"><?= lang('Forgot your password'); ?>?</a>
                <hr>
            </div>
            <div class="login-nav center size-13">
                <?= lang('No account available'); ?>?
                <br>
                <a href="/register"><?= lang('Sign up'); ?></a>
            </div>
        </form>
    </div>
</div>