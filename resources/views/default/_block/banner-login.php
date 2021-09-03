<div class="login-nav-home no-mob">
  <div class="pt15 pr15 pb15 pl15">
    <form class="" action="/login" method="post">
      <?php csrf_field(); ?>
      <div class="mb20">
        <label for="email" class="form-label">Email</label>
        <input type="email" placeholder="<?= lang('Enter'); ?>  e-mail" name="email" class="form-input">
      </div>
      <div class="mb20">
        <label for="password" class="form-label"><?= lang('Password'); ?></label>
        <input type="password" placeholder="<?= lang('Enter your password'); ?>" name="password" class="form-input">
      </div>
      <div class="mb20 mb20 flex">
        <input type="checkbox" class="mr10" id="rememberme" class="left mr5" name="rememberme" value="1">
        <label id="rem-text" class="form-check-label size-15" for="rememberme">
        <span></span><?= lang('Remember me'); ?>
        </label>
      </div>
      <div class="mb20">
        <button type="submit" class="button-primary pt10 pr15 pb10 pl15 size-13 white">
          <?= lang('Sign in'); ?>
        </button>
      </div>
      <div class="center size-14">
        <?= lang('login-use-condition'); ?>
      </div>
      <div class="mt15 center size-14">
        <a class="gray-light" href="/recover"><?= lang('Forgot your password'); ?>?</a>
      </div>
    </form>
  </div>
</div>