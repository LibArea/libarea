<?= insert('/_block/form/select/topic', ['topic'  => $data['topic'], 'action' => 'add']); ?>

<?php if (!empty($data['showing-blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'blog'       => $data['blog'],
    'action'  => 'add',
    'title'    => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<fieldset>
  <label for="post_title"><?= __('app.heading'); ?> <sup class="red">*</sup></label>
  <input id="title" type="text" required="" name="title">
  <div class="help">6 - 250 <?= __('app.characters'); ?></div>
</fieldset>

<fieldset>
  <div class="mb5"><?= __('app.text'); ?> Q&A <sup class="red">*</sup></div>
  <textarea name="content"></textarea>
  <div class="help"><?= __('app.necessarily'); ?></div>
</fieldset>


<details class="mt15">
  <summary><?= __('app.other'); ?></summary>
  <?= insert('/content/publications/add/add-details', ['data' => $data]); ?>	
</details>

<p><?= Html::sumbit(__('app.create')); ?></p>