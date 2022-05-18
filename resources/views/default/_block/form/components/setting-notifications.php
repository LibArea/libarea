<?php
$pm = $data['notif']['setting_email_pm'] ?? false;
$ap = $data['notif']['setting_email_appealed'] ?? false;
?>

<fieldset>
  <label for="setting_email_pm"><?= __('app.message_PM'); ?></label>
  <select id="setting_email_pm" name="setting_email_pm">
    <option value="0" <?php if ($pm == 0) : ?>selected="" <?php endif; ?>><?= __('app.no'); ?></option>
    <option value="1" <?php if ($pm == 1) : ?>selected="" <?php endif; ?>><?= __('app.yes'); ?></option>
  </select>
</fieldset>

<fieldset>
  <label for="setting_email_appealed"><?= __('app.appeal_@'); ?></label>
  <select id="setting_email_appealed" name="setting_email_appealed">
    <option value="0" <?php if ($ap == 0) : ?>selected="" <?php endif; ?>><?= __('app.no'); ?></option>
    <option value="1" <?php if ($ap == 1) : ?>selected="" <?php endif; ?>><?= __('app.yes'); ?></option>
  </select>
</fieldset>

<fieldset>
  <input type="hidden" name="nickname" id="nickname" value="">
  <?= Html::sumbit(__('app.edit')); ?>
</fieldset>