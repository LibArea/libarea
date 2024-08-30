<fieldset>
  <label for="login"><?= __('app.nickname'); ?></label>
  <input name="login" id="login" type="text" required>
  <div class="help">>= 3 <?= __('app.characters'); ?> (<?= __('app.english'); ?>)</div>
  <small class="red"></small>
</fieldset>

<fieldset>
  <label for="email"><?= __('app.email'); ?></label>
  <input name="email" id="email" type="email" required>
  <div class="help"><?= __('app.work_email'); ?>...</div>
  <small class="red"></small>
</fieldset>

<fieldset>
  <label for="password"><?= __('app.password'); ?></label>
  <input id="password" name="password" type="password" autocomplete="off" required>
  <span class="showPassword"><svg class="icon">
      <use xlink:href="/assets/svg/icons.svg#eye"></use>
    </svg></span>
  <div class="help">>= 8 <?= __('app.characters'); ?>...</div>
  <small class="red"></small>
</fieldset>

<fieldset>
  <label for="password_confirm"><?= __('app.password'); ?></label>
  <input name="password_confirm" id="password_confirm" type="password" autocomplete="off" required>
  <small class="red"></small>
</fieldset>

<?= insert('/_block/form/captcha'); ?>

<fieldset>
  <?= Html::sumbit(__('app.registration')); ?>
  <a class="ml15 text-sm" href="<?= url('login'); ?>"><?= __('app.sign_in'); ?></a>
</fieldset>