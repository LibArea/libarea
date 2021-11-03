<div class="br-box-grey p15 mb15 br-rd5 bg-white size-14">
  <form class="" action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>
    <div class="mb20">
      <label for="email" class="block mb5">Email</label>
      <input type="email" id="email" placeholder="<?= Translate::get('enter'); ?>  e-mail" name="email" class="w-100 h30 bg-gray-100">
    </div>
    <div class="mb20">
      <label for="password" class="block mb5"><?= Translate::get('password'); ?></label>
      <input type="password" id="password" placeholder="<?= Translate::get('enter your password'); ?>" name="password" class="w-100 h30 bg-gray-100">
    </div>
    <div class="mb20 mb20 flex">
      <input type="checkbox" id="rememberme" class="left mr5" name="rememberme" value="1">
      <label id="rem-text" class="form-check-label size-15" for="rememberme">
        <span class="gray-light"><?= Translate::get('remember me'); ?></span>
      </label>
    </div>
    <div class="mb20">
      <button type="submit" class="button block br-rd5 white">
        <?= Translate::get('sign in'); ?>
      </button>
    </div>
    <div class="center size-14 gray-light">
      <?= Translate::get('login-use-condition'); ?>
    </div>
    <div class="mt15 center size-14">
      <a class="gray-light" href="<?= getUrlByName('recover'); ?>"><?= Translate::get('forgot your password'); ?>?</a>
    </div>
  </form>
</div>