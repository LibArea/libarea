<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>
                <div class="box wide">
                    <form class="" action="/recover/send" method="post">
                        <?php csrf_field(); ?>
                        <div class="boxline">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email">
                        </div>
                        <?php if (Lori\Config::get(Lori\Config::PARAM_CAPTCHA)) { ?>
                            <div class="captcha_data">
                                <div class="captcha_wrap">
                                    <div class="g-recaptcha" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?= Lori\Config::get(Lori\Config::PARAM_PUBLIC_KEY); ?>"></div>
                                    <script async defer nonce="" src="https://www.google.com/recaptcha/api.js?hl=ru"></script>
                                </div>
                            </div>  <br /> 
                        <?php } ?>
                        <div class="row">
                            <div class="boxline">
                                <button type="submit" class="button-primary"><?= lang('Reset'); ?></button>
                                <small>
                                    <?php if(!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
                                        <span class="otst"><a href="/register"><?= lang('Sign up'); ?></a></span>
                                    <?php } ?>
                                    <span class="otst"><a href="/login"><?= lang('Sign in'); ?></a></span>
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>            
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('info_recover'); ?>
            </div>
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>
