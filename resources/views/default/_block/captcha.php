<?php if (Lori\Config::get(Lori\Config::PARAM_CAPTCHA)) { ?>
  <div class="boxline captcha_data">
    <div class="captcha_wrap">
      <div class="g-recaptcha" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?= Lori\Config::get(Lori\Config::PARAM_PUBLIC_KEY); ?>"></div>
      <script async defer src="https://www.google.com/recaptcha/api.js?hl=ru"></script>
    </div>
  </div>
<?php } ?>