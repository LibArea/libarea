<div class="border-box-1 p15 mb15 br-rd5 bg-white size-14">
  <form class="" action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>
    <div class="mb20">
      <label for="email" class="block mb5">Email</label>
      <input type="email" id="email" placeholder="<?= lang('enter'); ?>  e-mail" name="email" class="w-100 h30 bg-gray-100">
    </div>
    <div class="mb20">
      <label for="password" class="block mb5"><?= lang('password'); ?></label>
      <input type="password" id="password" placeholder="<?= lang('enter your password'); ?>" name="password" class="w-100 h30 bg-gray-100">
    </div>
    <div class="mb20 mb20 flex">
      <input type="checkbox" id="rememberme" class="left mr5" name="rememberme" value="1">
      <label id="rem-text" class="form-check-label size-15" for="rememberme">
        <span class="gray-light"><?= lang('remember me'); ?></span>
      </label>
    </div>
    <div class="mb20">
      <button type="submit" class="button block br-rd5 white">
        <?= lang('sign in'); ?>
      </button>
    </div>
    <div class="center size-14 gray-light">
      <?= lang('login-use-condition'); ?>
    </div>
    <div class="mt15 center size-14">
      <a class="gray-light" href="<?= getUrlByName('recover'); ?>"><?= lang('forgot your password'); ?>?</a>
    </div>
  </form>
</div>