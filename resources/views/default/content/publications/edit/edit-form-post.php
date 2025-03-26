<?php if ($container->user()->admin()) : ?>
  <fieldset>
    <label for="post_slug">SLUG (URL)</label>
    <input minlength="6" maxlength="250" value="<?= $item['post_slug']; ?>" type="text" required name="post_slug">
    <div class="help">> 6 <?= __('app.characters'); ?></div>
  </fieldset>
<?php endif; ?>

<?= insert('/_block/form/select/topic', ['data' => $data, 'action' => 'edit']); ?>

<?php if (!empty($data['blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'data'        => $data,
    'action'      => 'edit',
    'title'       => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<?= insert('/_block/form/editor/toolbar-img', ['height'  => '300px', 'content' => $item['post_content'], 'title' => __('app.post'), 'type' => 'post-telo', 'id' => $item['post_id']]); ?>



<?php if ($container->access()->limitTl(2)) : ?>
  <?php if ($item['post_draft'] == 1) : ?>
    <fieldset>
      <input type="checkbox" name="post_draft" <?php if ($item['post_draft'] == 1) : ?>checked <?php endif; ?>> <?= __('app.draft_post'); ?>
    </fieldset>
  <?php endif; ?>
<?php endif; ?>

<details class="mt15">
  <summary><?= __('app.other'); ?></summary>

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

<?php if ($item['post_type'] == 'post') : ?>
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

</details>

<p>
  <input type="hidden" name="id" value="<?= $item['post_id']; ?>">
  <?= Html::sumbit(__('app.edit')); ?>
</p>