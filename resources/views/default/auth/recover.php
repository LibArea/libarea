<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <h1><?= lang('Password Recovery'); ?></h1>
    <form class="form mini" action="/recover/send" method="post">
      <?php csrf_field(); ?>

      <?php field('input', [
        ['title' => lang('Email'), 'type' => 'email', 'name' => 'email', 'value' => ''],
      ]); ?>

      <?= returnBlock('captcha'); ?>

      <div class="boxline">
        <button type="submit" class="button"><?= lang('Reset'); ?></button>
        <?php if (!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
          <span class="mr5 ml15 size-13"><a href="/register"><?= lang('Sign up'); ?></a></span>
        <?php } ?>
        <span class="mr5 ml15 size-13"><a href="/login"><?= lang('Sign in'); ?></a></span>
      </div>
    </form>
  </main>
  <?= returnBlock('aside-lang', ['lang' => lang('info-recover')]); ?>
</div>