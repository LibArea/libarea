<fieldset>
  <label for="title"><?= __('app.heading'); ?></label>
  <input minlength="6" maxlength="250" id="title" value="<?= htmlEncode($item['post_title']); ?>" type="text" required name="title">
  <div class="help">6 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <label for="post_slug">SLUG (URL)</label>
  <input minlength="6" maxlength="250" value="<?= $item['post_slug']; ?>" type="text" required name="post_slug">
  <div class="help">> 6 <?= __('app.characters'); ?></div>
</fieldset>

<?= insert('/_block/form/select/topic', ['data' => $data, 'action' => 'edit']); ?>

<?= insert('/_block/form/select/section', ['data' => $data]); ?>

<?php if ($item['post_url']) : ?>
  <div class="mb20 2flex">
    <div class="mb5" for="post_title">URL:
      <a target="_blank" rel="noreferrer ugc" href="<?= $item['post_url']; ?>" class="text-sm">
        <?= $item['post_url']; ?>
      </a>
    </div>
    <?php if ($item['post_thumb_img']) : ?>
      <?= Img::image($item['post_thumb_img'], $item['post_title'], 'w94', 'post', 'thumbnails'); ?>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if ($item['post_type'] == 'post') : ?>
  <?= insert('/_block/form/cropper/post-foto', ['post' => $item]); ?>
<?php endif; ?>

<?= insert('/_block/form/editor/toolbar-img', ['height'  => '300px', 'content' => $item['post_content'], 'title' => __('app.post'), 'type' => 'post-telo', 'id' => $item['post_id']]); ?>

<?php if ($container->access()->limitTl(2)) : ?>
  <?php if ($item['post_draft'] == 1) : ?>
    <fieldset>
      <input type="checkbox" name="post_draft" <?php if ($item['post_draft'] == 1) : ?>checked <?php endif; ?>> <?= __('app.is_draft'); ?>
    </fieldset>
  <?php endif; ?>
<?php endif; ?>

<p>
  <input type="hidden" name="id" value="<?= $item['post_id']; ?>">
  <?= Html::sumbit(__('app.edit')); ?>
</p>