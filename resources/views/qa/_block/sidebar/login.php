<div class="br-box-gray p15 mb15 br-rd5 bg-white text-sm">
  <form class="" action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>
    <div class="mb20">
      <label for="email" class="block mb5">Email</label>
      <input type="email" id="email" placeholder="<?= Translate::get('enter'); ?> e-mail" name="email" class="w-100 h30 pl5">
    </div>
    <div class="mb20">
      <label for="password" class="block mb5"><?= Translate::get('password'); ?></label>
      <input type="password" id="password" placeholder="<?= Translate::get('enter your password'); ?>" name="password" class="w-100 h30 pl5">
    </div>
    <div class="mb20 mb20 flex">
      <input type="checkbox" id="rememberme" class="left mr5" name="rememberme" value="1">
      <label id="rem-text" class="form-check-label" for="rememberme">
        <span class="gray-600"><?= Translate::get('remember me'); ?></span>
      </label>
    </div>
    <div class="mb20">
      <?= sumbit(Translate::get('sign in')); ?>
    </div>
    <div class="center gray-600">
      <?= Translate::get('login-use-condition'); ?>
    </div>
    <div class="mt15 center">
      <a class="gray-600" href="<?= getUrlByName('recover'); ?>"><?= Translate::get('forgot your password'); ?>?</a>
    </div>
  </form>
</div>