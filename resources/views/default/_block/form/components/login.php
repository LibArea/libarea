<fieldset class="max-w300">
  <input name="email" type="email" placeholder="<?= __('app.email'); ?>" required="">
</fieldset>

<fieldset class="max-w300" >
  <input id="password" name="password" type="password" placeholder="<?= __('app.password'); ?>" required="">
  <span class="showPassword"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#eye"></use></svg></span>
</fieldset>

<?= component('rememberme'); ?>

<?= Html::sumbit(__('app.sign_in')); ?>