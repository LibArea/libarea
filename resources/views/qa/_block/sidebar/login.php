<div class="br-box-gray p15 mb15 br-rd5 bg-white text-sm">
  <form action="<?= getUrlByName('login'); ?>" method="post">
    <?php csrf_field(); ?>
    <fieldset class="mt0">
      <label for="email">Email</label>
      <input type="email" id="email" placeholder="<?= Translate::get('enter'); ?> e-mail" name="email">
    </fieldset>
    <fieldset>
      <label for="password"><?= Translate::get('password'); ?></label>
      <input type="password" id="password" placeholder="<?= Translate::get('enter your password'); ?>" name="password">
      <span class="showPassword absolute gray-400 right5 mt5 text-lg"><i class="bi-eye"></i></span>
    </fieldset>
    <fieldset class="flex items-center">
      <input type="checkbox" id="rememberme" name="rememberme" value="1">
      <label id="rem-text" class="mb0 ml5 gray-600" for="rememberme">
        <?= Translate::get('remember me'); ?>
      </label>
    </fieldset>
    <fieldset>
      <?= sumbit(Translate::get('sign.in')); ?>
    </fieldset>
    <fieldset class="gray-600 center">
      <?= Translate::get('login.use.condition'); ?>
      <a href="<?= getUrlByName('recover'); ?>"><?= Translate::get('forgot.password'); ?>?</a>
    </fieldset>
  </form>
</div>