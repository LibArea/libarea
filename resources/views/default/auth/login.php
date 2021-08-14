<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1><?= $data['h1']; ?></h1>
                <div class="form mini">
                    <form class="" action="/login" method="post">
                        <?php csrf_field(); ?>
                        <div class="boxline">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-input" name="email" id="email" value="">
                        </div>
                        <div class="boxline">
                            <label class="form-label" for="password"><?= lang('Password'); ?></label>
                            <input type="password" class="form-input" name="password" id="password" value="">
                        </div>
                        <div class="boxline">
                            <input type="checkbox" class="left mr5" id="rememberme" name="rememberme" value="1">
                            <label class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
                        </div>
                        <div class="boxline">
                            <button type="submit" class="button"><?= lang('Sign in'); ?></button>
                            <?php if (!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
                                <span class="mr5 ml5 size-13"><a href="/register"><?= lang('Sign up'); ?></a></span>
                            <?php } ?>
                            <span class="mr5 ml5 size-13"><a href="/recover"><?= lang('Forgot your password'); ?>?</a></span>
                        </div>
                    </form>

                    <?php if (Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
                        <?= lang('no-invate-txt'); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('info_login'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>