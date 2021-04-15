<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="left-ots">
        <h1 class="head"><?= $data['h1']; ?></h1>
        <div class="box wide">
            <form class="" action="/recover/send" method="post">
                <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email">
                </div>
                <?php if ($GLOBALS['conf']['captcha']) { ?>
                    <div class="captcha_data">
                        <div class="captcha_wrap">
                            <div class="g-recaptcha" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?php echo $GLOBALS['conf']['public_key']; ?>"></div>
                            <script async defer nonce="" src="https://www.google.com/recaptcha/api.js?hl=ru"></script>
                        </div>
                    </div>  <br /> 
                <?php } ?>
                <div class="row">
                    <div class="boxline">
                        <button type="submit" class="button-primary"><?= lang('Reset'); ?></button>
                        <small>
                            <?php if($GLOBALS['conf']['invite'] != 1) { ?>
                                <span class="left-ots"><a href="/register"><?= lang('Sign up'); ?></a></span>
                            <?php } ?>
                            <span class="left-ots"><a href="/login"><?= lang('Sign in'); ?></a></span>
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
