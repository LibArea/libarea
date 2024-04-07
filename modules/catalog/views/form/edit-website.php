<fieldset>
  <label for="title"><?= __('web.title'); ?> <strong class="red">*</strong></label>
  <input id="title" name="title" required="" type="text" value="<?=  htmlEncode($domain['item_title']); ?>">
  <div class="help">14 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="url">URL <strong class="red">*</strong></label>
  <input id="url" name="url" required="" type="url" value="<?= htmlEncode($domain['item_url']); ?>">
</fieldset>

<fieldset>
  <label for="content"><?= __('web.description'); ?> <strong class="red">*</strong></label>
  <textarea id="content" name="content" rows="5" required=""><?= $domain['item_content']; ?></textarea>
  <div class="help">> 24 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <input type="checkbox" name="close_replies" <?php if ($domain['item_close_replies'] == 1) : ?>checked <?php endif; ?>> <?= __('web.deny_replies'); ?>
</fieldset>

<?php if ($container->user()->admin()) : ?>
  <fieldset>
    <label for="post_slug">SLUG (URL)</label>
    <input minlength="6" maxlength="250" value="<?= $domain['item_slug']; ?>" type="text" required name="item_slug">
    <div class="help">> 6 <?= __('app.characters'); ?></div>
  </fieldset>

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
<?php endif; ?>

<div class="p15 bg-yellow">
  <h2 class="m0"><?= __('web.contact_information'); ?></h2>

  <fieldset>
    <label for="telephone"><?= __('web.telephone'); ?></label>
    <input type="text" name="telephone" value="<?= htmlEncode($domain['item_telephone']); ?>">
  </fieldset>

  <fieldset>
    <label for="email"><?= __('web.email'); ?></label>
    <input type="email" name="email" value="<?= htmlEncode($domain['item_email']); ?>">
  </fieldset>

  <fieldset>
    <label for="vk"><?= __('web.vk'); ?></label>
    <input type="url" name="vk" value="<?= htmlEncode($domain['item_vk']); ?>">
  </fieldset>

  <fieldset>
    <label for="telegram"><?= __('web.telegram'); ?></label>
    <input type="text" name="telegram" value="<?= htmlEncode($domain['item_telegram']); ?>">
  </fieldset>
</div>

<?php if ($container->user()->admin()) : ?>
  <div class="p15 bg-lightgray mt15">
    <h2 class="m0"><?= __('web.soft'); ?></h2>

    <fieldset>
      <input type="checkbox" name="soft" <?php if ($domain['item_is_soft'] == 1) : ?>checked <?php endif; ?>> <?= __('web.there_program'); ?>
    </fieldset>

    <fieldset>
      <input type="checkbox" name="github" <?php if ($domain['item_is_github'] == 1) : ?>checked <?php endif; ?>> <?= __('web.hosted_github'); ?>
    </fieldset>

    <fieldset>
      <label for="github_url"><?= __('web.url_github'); ?></label>
      <input id="github_url" name="github_url" type="url" value="<?= htmlEncode($domain['item_github_url']); ?>">
    </fieldset>

    <fieldset>
      <label for="title_soft"><?= __('web.title'); ?></label>
      <input id="title_soft" name="title_soft" type="text" value="<?= htmlEncode($domain['item_title_soft']); ?>">
    </fieldset>

    <fieldset>
      <label for="content_soft"><?= __('web.description'); ?></label>
      <textarea id="content_soft" name="content_soft"><?= $domain['item_content_soft']; ?></textarea>
    </fieldset>
  </div>
<?php endif; ?>

<?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_poll'))) : ?>
  <?= insert('/_block/form/select/poll', ['poll' => $poll]); ?>
<?php endif; ?>  