<fieldset>
  <label for="email"><?= __('app.old'); ?></label>
  <input id="password" name="password" type="password" required="">
  <span class="showPassword"><svg class="icons">
      <use xlink:href="/assets/svg/icons.svg#eye"></use>
    </svg></span>
</fieldset>

<fieldset>
  <label for="email"><?= __('app.new'); ?></label>
  <input name="password2" type="password" required="">
  <div class="help">8 - 32 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="email"><?= __('app.repeat'); ?></label>
  <input name="password3" type="password" required="">
</fieldset>

<fieldset>
  <input type="hidden" name="nickname" id="nickname" value="">
  <?= Html::sumbit(__('app.edit')); ?>
</fieldset>