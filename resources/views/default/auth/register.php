<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <h1><?= lang('Sign up'); ?></h1>
    <div class="form mini">
      <form class="" action="/register/add" method="post">
        <?php csrf_field(); ?>

        <div class="boxline">
          <label class="form-label" for="login"><?= lang('Nickname'); ?></label>
          <input type="text" class="form-input" name="login" id="login" minlength="3" pattern="^[a-zA-Z0-9\s]+$">
          <div class="box_h gray">>= 3 <?= lang('characters'); ?></div>
        </div>

        <?php field_input(array(
          array('title' => lang('Email'), 'type' => 'email', 'name' => 'email', 'value' => '', 'help' => lang('Work e-mail (to activate your account)')),
          array('title' => lang('Password'), 'type' => 'password', 'name' => 'password', 'value' => '', 'min' => 8, 'max' => 32),
          array('title' => lang('Repeat the password'), 'type' => 'password', 'name' => 'password_confirm', 'value' => '', 'min' => 8, 'max' => 32),
        )); ?>

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
            <span class="mr5 ml15 size-13"><a href="/login"><?= lang('Sign in'); ?></a></span>
          </div>
        </div>
      </form>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info-security'); ?>
    </div>
  </aside>
</div>