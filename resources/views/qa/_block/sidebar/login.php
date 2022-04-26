<div class="br-gray box text-sm">
  <form action="<?= url('login'); ?>" method="post">
    <?php csrf_field(); ?>
    <fieldset class="mt0">
      <label for="email">Email</label>
      <input type="email" id="email" placeholder="<?= __('enter'); ?> e-mail" name="email">
    </fieldset>
    <fieldset>
      <label for="password"><?= __('password'); ?></label>
      <input type="password" id="password" placeholder="<?= __('enter.password'); ?>" name="password">
      <span class="showPassword absolute gray-600 right5 mt5 text-lg"><i class="bi-eye"></i></span>
    </fieldset>
    <fieldset class="flex items-center">
      <input type="checkbox" id="rememberme" name="rememberme" value="1">
      <label id="rem-text" class="mb0 ml5 gray-600" for="rememberme">
        <?= __('remember.me'); ?>
      </label>
    </fieldset>
    <fieldset>
      <?= Html::sumbit(__('sign.in')); ?>
    </fieldset>
    <fieldset class="gray-600 center">
      <?= __('login.use.condition'); ?>
      <a href="<?= url('recover'); ?>"><?= __('forgot.password'); ?>?</a>
    </fieldset>
  </form>
</div>