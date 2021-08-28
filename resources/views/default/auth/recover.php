<div class="wrap">
  <main>
    <div class="white-box">
      <div class="pt5 pr15 pb5 pl15">
        <h1><?= lang('Password Recovery'); ?></h1>
        <div class="form mini">
          <form class="" action="/recover/send" method="post">
            <?php csrf_field(); ?>
            <div class="boxline">
              <label class="form-label" for="email">Email</label>
              <input class="form-input" type="text" name="email" id="email">
            </div>
            <?php if (Lori\Config::get(Lori\Config::PARAM_CAPTCHA)) { ?>
              <div class="captcha_data">
                <div class="captcha_wrap">
                  <div class="g-recaptcha" data-theme="light" data-size="normal" data-callback="captcha_ready" data-sitekey="<?= Lori\Config::get(Lori\Config::PARAM_PUBLIC_KEY); ?>"></div>
                  <script async defer nonce="" src="https://www.google.com/recaptcha/api.js?hl=ru"></script>
                </div>
              </div> <br />
            <?php } ?>
            <div class="row">
              <div class="boxline">
                <button type="submit" class="button"><?= lang('Reset'); ?></button>
                <?php if (!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
                  <span class="mr5 ml15 size-13"><a href="/register"><?= lang('Sign up'); ?></a></span>
                <?php } ?>
                <span class="mr5 ml15 size-13"><a href="/login"><?= lang('Sign in'); ?></a></span>
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
        <?= lang('info_recover'); ?>
      </div>
    </div>
  </aside>
</div>