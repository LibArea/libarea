<fieldset class="form-big">
  <div class="form-label input-label"><label><?= __('app.heading'); ?></label></div>
  <div class="form-element">
    <input minlength="6" maxlength="250" id="title" value="<?= htmlEncode($item['post_title']); ?>" type="text" required name="title">
    <div class="help">6 - 250 <?= __('app.characters'); ?></div>
  </div>
</fieldset>

<?php if ($container->user()->admin()) : ?>
  <fieldset>
    <div class="form-label input-label"><label>SLUG (URL)</label></div>
    <div class="form-element">
      <input minlength="6" maxlength="250" value="<?= $item['post_slug']; ?>" type="text" required name="post_slug">
      <div class="help">> 6 <?= __('app.characters'); ?></div>
    </div>
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

<?php if ($container->user()->admin()) : ?>
  <?= insert('/_block/form/select/section', ['data' => $data]); ?>
<?php endif; ?>

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

<?= insert('/_block/form/editor/toolbar-img', ['height'  => '300px', 'content' => $item['post_content'], 'title' => __('app.post'), 'type' => 'post-telo', 'id' => $item['post_id']]); ?>

<details class="mt15">
  <summary><?= __('app.other'); ?></summary>
  <?= insert('/content/publications/edit/edit-details', ['data' => $data, 'item' => $item]); ?>
</details>