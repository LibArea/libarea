<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1><?= $data['h1']; ?></h1>
                <div class="form mini">
                    <form class="" action="/register/add" method="post">
                        <?php csrf_field(); ?>
                        <div class="boxline">
                            <label class="form-label" for="login"><?= lang('Nickname'); ?></label>
                            <input type="text" class="form-input" name="login" id="login" minlength="3" pattern="^[a-zA-Z0-9\s]+$">
                            <div class="box_h">>= 3 <?= lang('characters'); ?></div>
                        </div>
                        <div class="boxline">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-input" name="email" id="email">
                        </div>
                        <div class="boxline">
                            <label class="form-label" for="password"><?= lang('Password'); ?></label>
                            <input type="password" class="form-input" minlength="8" name="password" id="password">
                            <div class="box_h">>= 8 <?= lang('characters'); ?></div>
                        </div>
                        <div class="boxline">
                            <label class="form-label" for="password_confirm"><?= lang('Repeat the password'); ?></label>
                            <input type="password" class="form-input" minlength="8" name="password_confirm" id="password_confirm">
                        </div>
                        <?php if (Lori\Config::get(Lori\Config::PARAM_CAPTCHA)) { ?>
                            <div class="boxline captcha_data">
                                <div class="captcha_wrap">
                                    <div class="g-recaptcha" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?= Lori\Config::get(Lori\Config::PARAM_PUBLIC_KEY); ?>"></div>
                                    <script async defer src="https://www.google.com/recaptcha/api.js?hl=ru"></script>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="boxline">
                            <div class="boxline">
                                <button type="submit" class="button"><?= lang('Sign up'); ?></button>
                                <span class="mr5 ml5 size-13"><a href="/login"><?= lang('Sign in'); ?></a></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('info_security'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>