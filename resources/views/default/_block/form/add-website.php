<fieldset>
  <label for="title"><?= __('web.title'); ?> <strong class="red">*</strong></label>
  <input id="title" name="title" required="" type="text" value="">
  <div class="help">14 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="url">URL <strong class="red">*</strong></label>
  <input id="url" name="url" required="" type="text" value="">
</fieldset>

<fieldset>
  <label for="content"><?= __('web.description'); ?> <strong class="red">*</strong></label>
  <textarea id="content" name="content" rows="5" required=""></textarea>
  <div class="help">> 24 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <input type="checkbox" name="close_replies"> <?= __('web.deny_replies'); ?>
</fieldset>

<?php if (UserData::checkAdmin()) : ?>
  <fieldset>
    <input type="checkbox" name="published"> <?= __('web.posted'); ?>
  </fieldset>
<?php endif; ?>
