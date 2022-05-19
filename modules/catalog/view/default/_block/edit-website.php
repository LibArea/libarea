<fieldset>
  <label for="title"><?= __('web.title'); ?> <strong class="red">*</strong></label>
  <input id="title" name="title" required="" type="text" value="<?= $domain['item_title']; ?>">
  <div class="help">14 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="url">URL <strong class="red">*</strong></label>
  <input id="url" name="url" required="" type="text" value="<?= $domain['item_url']; ?>">
</fieldset>

<fieldset>
  <label for="content"><?= __('web.description'); ?> <strong class="red">*</strong></label>
  <textarea id="content" name="content" rows="5" required=""><?= $domain['item_content']; ?></textarea>
  <div class="help">> 24 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="close_replies"><?= __('web.deny_replies'); ?></label>
  <select id="close_replies" name="close_replies">
    <option value="0" <?php if ($domain['item_close_replies'] == 1) : ?>selected<?php endif; ?>><?= __('web.no'); ?></option>
    <option value="1" <?php if ($domain['item_close_replies'] == 1) : ?>selected<?php endif; ?>><?= __('web.yes'); ?></option>
  </select>
</fieldset>

<?php if (UserData::checkAdmin()) : ?>
  <fieldset>
    <label for="published"><?= __('web.posted'); ?></label>
    <select id="published" name="published">
      <option value="0" <?php if ($domain['item_published'] == 0) : ?>selected<?php endif; ?>><?= __('web.no'); ?></option>
      <option value="1" <?php if ($domain['item_published'] == 1) : ?>selected<?php endif; ?>><?= __('web.yes'); ?></option>
    </select>
  </fieldset>

  <fieldset>
    <label for="status"><?= __('web.status'); ?></label>
    <input id="status" name="status" type="text" value="<?= $domain['item_status_url']; ?>">
  </fieldset>

  <h2><?= __('web.soft'); ?></h2>

  <fieldset>
    <label for="soft"><?= __('web.there_program'); ?></label>
    <select id="soft" name="soft">
      <option value="0" <?php if ($domain['item_is_soft'] == 0) : ?>selected<?php endif; ?>><?= __('web.no'); ?></option>
      <option value="1" <?php if ($domain['item_is_soft'] == 1) : ?>selected<?php endif; ?>><?= __('web.yes'); ?></option>
    </select>
  </fieldset>

  <fieldset>
    <label for="github"><?= __('web.hosted_github'); ?></label>
    <select id="github" name="github">
      <option value="0" <?php if ($domain['item_is_github'] == 0) : ?>selected<?php endif; ?>><?= __('web.no'); ?></option>
      <option value="1" <?php if ($domain['item_is_github'] == 1) : ?>selected<?php endif; ?>><?= __('web.yes'); ?></option>
    </select>
  </fieldset>

  <fieldset>
    <label for="github_url"><?= __('web.url_github'); ?></label>
    <input id="github_url" name="github_url" type="text" value="<?= $domain['item_github_url']; ?>">
  </fieldset>

  <fieldset>
    <label for="title_soft"><?= __('web.title'); ?></label>
    <input id="title_soft" name="title_soft" type="text" value="<?= $domain['item_title_soft']; ?>">
  </fieldset>

  <fieldset>
    <label for="content_soft"><?= __('web.description'); ?></label>
    <textarea id="content_soft" name="content_soft"><?= $domain['item_content_soft']; ?></textarea>
  </fieldset>
<?php endif; ?>
