<div class="login-nav-home"> 
    <form class="" action="/login" method="post">
        <?php csrf_field(); ?>           
        <div class="login-nav">
            <label for="email">Email</label>
            <input type="text" placeholder="Введите  Email" name="email" id="email">
        </div>
        <div class="login-nav">
            <label for="password"><?= lang('Password'); ?></label>
            <input type="password" placeholder="Введите пароль" name="password" id="password">
        </div>
        <div class="login-nav">
            <input type="checkbox" id="rememberme" name="rememberme" value="1">
            <label id="rem-text" class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
        </div>
        <div class="login-nav">
            <button type="submit" class="button-primary"><?= lang('Sign in'); ?></button>
        </div>
        <div class="login-nav center small">
            Продолжая, вы соглашаетесь с <a href="/info/privacy">Условиями использования</a> сайта
        </div>
        <div class="login-nav center small">
            <a class="recover" href="/recover"><?= lang('forgot-password'); ?>?</a>
            <hr>
        </div>
        <div class="login-nav center small">
            <?= lang('No account available'); ?>?
            <br>
            <a href="/register"><?= lang('Sign up'); ?></a>
        </div>
     </form>
     <br>
</div> 