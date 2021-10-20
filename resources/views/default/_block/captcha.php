<?php if (Config::get('general.captcha')) { ?>
  <div class="mb20 captcha_data">
    <div class="captcha_wrap">
      <div class="g-recaptcha" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?= Config::get('general.public_key'); ?>"></div>
      <script async defer src="https://www.google.com/recaptcha/api.js?hl=ru"></script>
    </div>
  </div>
<?php } ?>