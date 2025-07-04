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

<?= insert('/_block/form/editor/toolbar-img', ['height'  => '300px', 'content' => $item['post_content'], 'title' => __('app.post'), 'type' => 'post-telo', 'id' => $item['post_id']]); ?>

<details class="mt15">
  <summary><?= __('app.other'); ?></summary>
  <?= insert('/content/publications/edit/edit-details', ['data' => $data, 'item' => $item]); ?>
</details>