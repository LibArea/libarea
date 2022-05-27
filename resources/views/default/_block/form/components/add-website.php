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
  <label for="close_replies"><?= __('web.deny_replies'); ?></label>
  <select id="close_replies" name="close_replies">
    <option value="0"><?= __('web.no'); ?></option>
    <option value="1"><?= __('web.yes'); ?></option>
  </select>
</fieldset>

<?php if (UserData::checkAdmin()) : ?>
  <fieldset>
    <label for="published"><?= __('web.posted'); ?></label>
    <select id="published" name="published">
      <option value="0"><?= __('web.no'); ?></option>
      <option value="1"><?= __('web.yes'); ?></option>
    </select>
  </fieldset>
<?php endif; ?>
