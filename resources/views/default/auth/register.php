<div class="wrap">
  <main class="white-box pt5 pr15 pb5 pl15">
    <h1><?= lang('Sign up'); ?></h1>
      <form class="form mini" action="/register/add" method="post">
        <?php csrf_field(); ?>

        <?php field('input', [
          ['title' => lang('Nickname'), 'type' => 'text', 'name' => 'login', 'value' => '', 'min' => 8, 'max' => 32, 'help' => '>= 3 ' . lang('characters') . ' (' . lang('english') . ')'],
          ['title' => lang('Email'), 'type' => 'email', 'name' => 'email', 'value' => '', 'help' => lang('Work e-mail (to activate your account)')],
          ['title' => lang('Password'), 'type' => 'password', 'name' => 'password', 'value' => '', 'min' => 8, 'max' => 32],
          ['title' => lang('Repeat the password'), 'type' => 'password', 'name' => 'password_confirm', 'value' => '', 'min' => 8, 'max' => 32],
        ]); ?>

        <?php captcha(); ?>

        <div class="boxline">
          <div class="boxline">
            <button type="submit" class="button"><?= lang('Sign up'); ?></button>
            <span class="mr5 ml15 size-13"><a href="/login"><?= lang('Sign in'); ?></a></span>
          </div>
        </div>
      </form>
  </main>
  <?= aside('lang', ['lang' => lang('info-security')]); ?>
</div>