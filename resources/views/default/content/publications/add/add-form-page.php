<fieldset>
  <label for="post_title"><?= __('app.heading'); ?> <sup class="red">*</sup></label>
  <input id="title" type="text" required="" name="title">
  <div class="help">6 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<?= insert('/_block/form/select/topic', ['topic'  => $data['topic'], 'action' => 'add']); ?>

<?= insert('/_block/form/select/section', ['data' => $data]); ?>

<?= insert('/_block/form/editor/toolbar-img', ['title' => __('app.post'), 'height' => '300px', 'type' => 'post-telo', 'id' => 0]); ?>

<fieldset>
  <input type="checkbox" name="draft"> <?= __('app.is_draft'); ?>
</fieldset>

<p><?= Html::sumbit(__('app.create')); ?></p>