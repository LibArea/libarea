<?php if ($container->access()->limitTl(2)) : ?>
  <?php if ($item['post_draft'] == 1) : ?>
    <fieldset>
      <input type="checkbox" name="post_draft" <?php if ($item['post_draft'] == 1) : ?>checked <?php endif; ?>> <?= __('app.is_draft'); ?>
    </fieldset>
  <?php endif; ?>
<?php endif; ?>

<?= insert('/_block/form/content-tl', ['data' => $item['post_tl']]); ?>

<fieldset>
  <input type="checkbox" name="closed" <?php if ($item['post_closed'] == 1) : ?>checked <?php endif; ?>> <?= __('app.post_closed'); ?>
</fieldset>

<fieldset>
  <input type="checkbox" name="translation" <?php if ($item['post_translation'] == 1) : ?>checked <?php endif; ?>> <?= __('app.post_translation'); ?>
</fieldset>

<?php if ($container->user()->admin()) : ?>
  <fieldset>
    <input type="checkbox" name="top" <?php if ($item['post_top'] == 1) : ?>checked <?php endif; ?>> <?= __('app.pin'); ?>
  </fieldset>
<?php endif; ?>


<?php if (config('feed', 'nsfw')) : ?>
  <fieldset>
    <input type="checkbox" name="nsfw" <?php if ($item['post_nsfw'] == 1) : ?>checked <?php endif; ?>> <?= __('app.nsfw_post'); ?>
  </fieldset>
<?php endif; ?>

<fieldset>
  <input type="checkbox" name="hidden" <?php if ($item['post_hidden'] == 1) : ?>checked <?php endif; ?>> <?= __('app.hidden_post'); ?>
  <div class="help"><?= __('app.hidden_post_help'); ?></div>
</fieldset>

<?php if ($container->user()->admin()) : ?>
  <?= insert('/_block/form/select/user', ['user' => $data['user']]); ?>
<?php endif; ?>

<?php if ($item['post_type'] != 'post') : ?>
  <?= insert('/_block/form/select/related-posts', ['data' => $data]); ?>
<?php endif; ?>

<?php if ($container->user()->admin()) : ?>
  <fieldset>
    <label for="post_title"><?= __('app.id_merged_post'); ?></label>
    <input value="<?= $item['post_merged_id']; ?>" type="text" name="post_merged_id">
    <div class="help"><?= __('app.post_merged_info'); ?></div>
  </fieldset>
<?php endif; ?>

<?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_poll'))) : ?>
  <?= insert('/_block/form/select/poll', ['poll' => $data['poll']]); ?>
<?php endif; ?>