<fieldset class="max-w300">
  <input name="email" type="email" placeholder="<?= __('app.email'); ?>" required="">
</fieldset>

<fieldset class="max-w300" >
  <input id="password" name="password" type="password" placeholder="<?= __('app.password'); ?>" required="">
  <span class="showPassword"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#eye"></use></svg></span>
</fieldset>

<fieldset class="rememberme ">
  <input id="rememberme" name="rememberme" type="checkbox" value="1">
  <label for="rememberme"><?= __('app.remember_me'); ?></label>
  <div class="text-sm gray-600"></div>
</fieldset>

<?= Html::sumbit(__('app.sign_in')); ?>