<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <h1><?= $data['h1']; ?></h1>
    <div class="box wide">
        <form class="" action="/login" method="post">
            <?php csrf_field(); ?>
            <div class="boxline">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="">
            </div>
            <div class="boxline">
                <label for="password"><?= lang('Password'); ?></label>
                <input type="password" name="password" id="password" value="">
            </div>
            <div class="boxline">
                <input type="checkbox" id="rememberme" name="rememberme" value="1">
                <label class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
            </div>
            <div class="row">
                <div class="boxline">
                    <button type="submit" class="button-primary"><?= lang('Sign in'); ?></button>
                    <small>
                        <?php if(!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
                            <span class="menu-lf"><a href="/register"><?= lang('Sign up'); ?></a></span>
                        <?php } ?>
                        <span class="menu-lf"><a href="/recover"><?= lang('forgot-password'); ?>?</a></span>
                    </small>
                </div>
            </div>
        </form>

        <?php if(Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
            <?= lang('no-invate-txt'); ?> 
        <?php } ?>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
