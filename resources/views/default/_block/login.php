<div class="login-nav-home white-box">
    <div class="pt15 pr15 pb5 pl15">
        <form class="" action="/login" method="post">
            <?php csrf_field(); ?>
            <div class="mb20">
                <label for="email">Email</label>
                <input type="email" placeholder="<?= lang('Enter'); ?>  e-mail" name="email" id="email">
            </div>
            <div class="mb20">
                <label for="password"><?= lang('Password'); ?></label>
                <input type="password" placeholder="<?= lang('Enter your password'); ?>" name="password" id="password">
            </div>
            <div class="mb20">
                <input type="checkbox" id="rememberme" name="rememberme" value="1">
                <label id="rem-text" class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
            </div>
            <div class="mb20">
                <button type="submit" class="button-primary pt10 pr15 pb10 pl15 size-13 white">
                    <?= lang('Sign in'); ?>
                </button>
            </div>
            <div class="center size-13">
                Продолжая, вы соглашаетесь с <a href="/info/privacy">Условиями использования</a> сайта
            </div>
            <div class="mb20 mt15 center size-13">
                <a class="gray-light" href="/recover"><?= lang('Forgot your password'); ?>?</a>
                <hr>
            </div>
            <div class="mb20 center size-13">
                <?= lang('No account available'); ?>?
                <br>
                <a href="/register"><?= lang('Sign up'); ?></a>
            </div>
        </form>
    </div>
</div>