<?= insert('/_block/form/select/topic', ['topic'  => $data['topic'], 'action' => 'add']); ?>

<?php if (!empty($data['showing-blog'])) : ?>
  <?= insert('/_block/form/select/blog', [
    'blog'       => $data['blog'],
    'action'  => 'add',
    'title'    => __('app.blogs'),
  ]); ?>
<?php endif; ?>

<fieldset class="form-bg">
  <div class="form-label input-label"><label><?= __('app.heading'); ?></label></div>
  <div class="form-element">
    <input id="title" type="text" required="" name="title">
    <div class="help">6 - 250 <?= __('app.characters'); ?></div>
  </div>
</fieldset>

<fieldset class="flex items-center gap-sm form-element">
  <input id="link" placeholder="<?= __('app.url_parsing'); ?>" class="post_url" type="text" name="post_url" />
  <div class="w-30"><input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= __('app.to_extract'); ?>" /></div>
</fieldset>

<fieldset>
  <div class="form-label input-label"><label><?= __('app.content'); ?> URL</label></div>
  <div class="form-element">
    <textarea class="url" rows="5" cols="33" name="content"></textarea>
    <div class="help"><?= __('app.necessarily'); ?></div>
  </div>
</fieldset>

<details class="mt15">
  <summary><?= __('app.other'); ?></summary>
  <?= insert('/content/publications/add/add-details', ['data' => $data]); ?>
</details>

<p><?= Html::sumbit(__('app.create')); ?></p>