<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15 mini">
    <h1><?= lang('Sign in'); ?></h1>
    <form class="" action="<?= getUrlByName('login'); ?>" method="post">
      <?php csrf_field(); ?>

      <?php field('input', [
        ['title' => lang('Email'), 'type' => 'email', 'name' => 'email', 'value' => ''],
        ['title' => lang('Password'), 'type' => 'password', 'name' => 'password', 'value' => ''],
      ]); ?>

      <div class="boxline">
        <input type="checkbox" class="left mr5" id="rememberme" name="rememberme" value="1">
        <label class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
      </div>
      <div class="boxline">
        <button type="submit" class="button"><?= lang('Sign in'); ?></button>
        <?php if (!Agouti\Config::get(Agouti\Config::PARAM_INVITE)) { ?>
          <span class="mr5 ml5 size-13"><a href="<?= getUrlByName('register'); ?>"><?= lang('Sign up'); ?></a></span>
        <?php } ?>
        <span class="mr5 ml5 size-13"><a href="<?= getUrlByName('recover'); ?>"><?= lang('Forgot your password'); ?>?</a></span>
      </div>
    </form>

    <?php if (Agouti\Config::get(Agouti\Config::PARAM_INVITE)) { ?>
      <?= lang('no-invate-txt'); ?>
    <?php } ?>
  </main>
  <?= returnBlock('aside-lang', ['lang' => lang('info-login')]); ?>
</div>