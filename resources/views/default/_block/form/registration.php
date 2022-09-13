<fieldset>
  <label for="login"><?= __('app.nickname'); ?></label>
  <input name="login" type="text" required>
  <div class="help">>= 3 <?= __('app.characters'); ?> (<?= __('app.english'); ?>)</div>
</fieldset>

<fieldset>
  <label for="email"><?= __('app.email'); ?></label>
  <input name="email" type="email" required>
  <div class="help"><?= __('app.work_email'); ?>...</div>
</fieldset>

<fieldset>
  <label for="password"><?= __('app.password'); ?></label>
  <input id="password" name="password" type="password" required>
  <span class="showPassword"><svg class="icons"><use xlink:href="/assets/svg/icons.svg#eye"></use></svg></span>
  <div class="help">>= 8 <?= __('app.characters'); ?>...</div>
</fieldset>

<fieldset>
  <label for="password_confirm"><?= __('app.password'); ?></label>
  <input name="password_confirm" type="password" required>
</fieldset>

<?= insert('/_block/form/captcha'); ?>

<fieldset>
  <?= Html::sumbit(__('app.registration')); ?>
  <a class="ml15 text-sm" href="<?= url('login'); ?>"><?= __('app.sign_in'); ?></a>
</fieldset>