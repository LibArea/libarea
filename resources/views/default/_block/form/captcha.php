<?php if (config('integration', 'captcha')) : ?>
   <div class="g-recaptcha mb15" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?= config('integration', 'captcha_public_key'); ?>"></div>
   <script async defer src="https://www.google.com/recaptcha/api.js?hl=<?= Translate::getLang(); ?>"></script>
<?php endif; ?>