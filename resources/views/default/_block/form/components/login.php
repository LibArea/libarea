<fieldset class="max-w300">
  <input name="email" type="email" placeholder="<?= __('app.email'); ?>" required="">
</fieldset>

<fieldset class="max-w300" >
  <input id="password" name="password" type="password" placeholder="<?= __('app.password'); ?>" required="">
  <span class="showPassword"><i class="bi-eye"></i></span>
</fieldset>

<?= component('rememberme'); ?>

<?= Html::sumbit(__('app.sign_in')); ?>