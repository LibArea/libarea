<fieldset>
	<div class="form-label input-label"><label><?= __('app.old_password'); ?></label></div>
	<div class="form-element">
	  <input id="password" name="password" type="password" required="">
	  <span class="showPassword"><svg class="icon">
		  <use xlink:href="/assets/svg/icons.svg#eye"></use>
		</svg></span>
		</div>
</fieldset>

<fieldset>
	<div class="form-label input-label"><label><?= __('app.new_password'); ?></label></div>
	<div class="form-element">
	  <input name="password2" type="password" required="">
	  <div class="help">8 - 32 <?= __('app.characters'); ?></div>
		</div>
</fieldset>
 
<fieldset>
	<div class="form-label input-label"><label><?= __('app.confirm_password'); ?></label></div>
	<div class="form-element">
	  <input name="password3" type="password" required="">
		</div>
</fieldset>

<fieldset>
  <input type="hidden" name="nickname" id="nickname" value="">
  <?= Html::sumbit(__('app.edit')); ?>
</fieldset>