<?php
$pm = $data['notif']['setting_email_pm'] ?? false;
$ap = $data['notif']['setting_email_appealed'] ?? false;
?>

<fieldset>
	<div class="form-label input-label"><label><?= __('app.message_PM'); ?></label></div>
	<div class="form-element">
		<select id="setting_email_pm" name="setting_email_pm">
			<option value="0" <?php if ($pm == 0) : ?>selected="" <?php endif; ?>><?= __('app.no'); ?></option>
			<option value="1" <?php if ($pm == 1) : ?>selected="" <?php endif; ?>><?= __('app.yes'); ?></option>
		</select>
	</div>
</fieldset>

<fieldset>
	<div class="form-label input-label"><label><?= __('app.appeal_@'); ?></label></div>
	<div class="form-element">
		<select id="setting_email_appealed" name="setting_email_appealed">
			<option value="0" <?php if ($ap == 0) : ?>selected="" <?php endif; ?>><?= __('app.no'); ?></option>
			<option value="1" <?php if ($ap == 1) : ?>selected="" <?php endif; ?>><?= __('app.yes'); ?></option>
		</select>
	</div>
</fieldset>

<fieldset>
	<input type="hidden" name="nickname" id="nickname" value="">
	<?= Html::sumbit(__('app.edit')); ?>
</fieldset>