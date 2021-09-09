<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15 mini">
    <h1><?= lang('Sign in'); ?></h1>
    <form class="" action="/login" method="post">
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
        <?php if (!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
          <span class="mr5 ml5 size-13"><a href="/register"><?= lang('Sign up'); ?></a></span>
        <?php } ?>
        <span class="mr5 ml5 size-13"><a href="/recover"><?= lang('Forgot your password'); ?>?</a></span>
      </div>
    </form>

    <?php if (Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
      <?= lang('no-invate-txt'); ?>
    <?php } ?>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('info-login'); ?>
    </div>
  </aside>
</div>