<fieldset class="form-big">
  <div class="form-label input-label"><label><?= __('app.heading'); ?></label></div>
  <div class="form-element">
    <input id="title" type="text" required="" name="title">
    <div class="help">6 - 250 <?= __('app.characters'); ?></div>
  </div>
</fieldset>

<?= insert('/_block/form/select/topic', ['topic'  => $data['topic'], 'action' => 'add']); ?>

<?php if (!empty($data['showing-blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'blog'       => $data['blog'],
    'action'  => 'add',
    'title'    => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<?= insert('/_block/form/cropper/post-foto', ['post' => []]); ?>

<?= insert('/_block/form/editor/toolbar-img', ['height' => '300px', 'type' => 'post-telo', 'id' => 0]); ?>

<details class="mt15">
  <summary><?= __('app.other'); ?></summary>
  <?= insert('/content/publications/add/add-details', ['data' => $data]); ?>
</details>