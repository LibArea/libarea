<div class="login-nav-home white-box">
  <div class="pt15 pr15 pb5 pl15">
    <form class="" action="/login" method="post">
      <?php csrf_field(); ?>
      <div class="mb20">
        <label for="email" class="size-15">Email</label>
        <input type="email" placeholder="<?= lang('Enter'); ?>  e-mail" name="email" id="email">
      </div>
      <div class="mb20">
        <label for="password" class="size-15"><?= lang('Password'); ?></label>
        <input type="password" placeholder="<?= lang('Enter your password'); ?>" name="password" id="password">
      </div>
      <div class="mb20">
        <input type="checkbox" class="mr5" id="rememberme" class="left mr5" name="rememberme" value="1">
        <label id="rem-text" class="form-check-label size-15" for="rememberme"><?= lang('Remember me'); ?></label>
      </div>
      <div class="mb20">
        <button type="submit" class="button-primary pt10 pr15 pb10 pl15 size-13 white">
          <?= lang('Sign in'); ?>
        </button>
      </div>
      <div class="center size-13">
        <?= lang('login-use-condition'); ?>
      </div>
      <div class="mb20 mt15 center size-13">
        <a class="gray-light" href="/recover"><?= lang('Forgot your password'); ?>?</a>
        <hr>
      </div>
      <div class="mb20 center size-13">
        <?= lang('No account available'); ?>?
        <br>
        <a href="/register"><?= lang('Sign up'); ?></a>
      </div>
    </form>
  </div>
</div>