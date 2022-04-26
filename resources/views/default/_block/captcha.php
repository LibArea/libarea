<?php if (config('general.captcha')) : ?>
   <div class="g-recaptcha mb15" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?= config('general.public_key'); ?>"></div>
   <script async defer src="https://www.google.com/recaptcha/api.js?hl=ru"></script>
<?php endif; ?>