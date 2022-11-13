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
  <input type="checkbox" name="close_replies" <?php if ($domain['item_close_replies'] == 1) : ?>checked <?php endif; ?>> <?= __('web.deny_replies'); ?>
</fieldset>

<?php if (UserData::checkAdmin()) : ?>
  <fieldset>
    <input type="checkbox" name="published" <?php if ($domain['item_published'] == 1) : ?>checked <?php endif; ?>> <span class="red"><?= __('web.posted'); ?></span>
  </fieldset>
  <br>
  <fieldset>
    <input type="checkbox" name="forum" <?php if ($domain['item_is_forum'] == 1) : ?>checked <?php endif; ?>> <?= __('web.there_forum'); ?>
  </fieldset>
  
  <fieldset>
    <input type="checkbox" name="portal" <?php if ($domain['item_is_portal'] == 1) : ?>checked <?php endif; ?>> <?= __('web.there_portal'); ?>
  </fieldset>
  
  <fieldset>
    <input type="checkbox" name="blog" <?php if ($domain['item_is_blog'] == 1) : ?>checked <?php endif; ?>> <?= __('web.there_blog'); ?>
  </fieldset>

  <fieldset>
    <input type="checkbox" name="reference" <?php if ($domain['item_is_reference'] == 1) : ?>checked <?php endif; ?>> <?= __('web.there_reference'); ?>
  </fieldset>

  <fieldset>
    <input type="checkbox" name="goods" <?php if ($domain['item_is_goods'] == 1) : ?>checked <?php endif; ?>> <?= __('web.there_goods'); ?>
  </fieldset>

  <h2><?= __('web.soft'); ?></h2>

  <fieldset>
    <input type="checkbox" name="soft" <?php if ($domain['item_is_soft'] == 1) : ?>checked <?php endif; ?>> <?= __('web.there_program'); ?>
  </fieldset>
  
  <fieldset>
    <input type="checkbox" name="github" <?php if ($domain['item_is_github'] == 1) : ?>checked <?php endif; ?>> <?= __('web.hosted_github'); ?>
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
