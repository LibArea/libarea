<?= insert('/_block/form/select/topic', ['topic'  => $data['topic'], 'action' => 'add']); ?>

<?php if (!empty($data['showing-blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'blog'       => $data['blog'],
    'action'  => 'add',
    'title'    => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<?= insert('/_block/form/editor/toolbar-img', ['title' => __('app.post'), 'height' => '300px', 'type' => 'post-telo', 'id' => 0]); ?>

<details class="mt15">
  <summary><?= __('app.other'); ?></summary>
  <?= insert('/content/publications/add/add-details', ['data' => $data]); ?>
</details>

<p><?= Html::sumbit(__('app.create')); ?></p>