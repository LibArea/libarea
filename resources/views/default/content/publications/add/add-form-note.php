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

<?php if ($container->access()->limitTl(config('trust-levels', 'tl_add_url'))) : ?>
  <fieldset class="flex items-center gap-sm">
    <input id="link" placeholder="<?= __('app.url_parsing'); ?>" class="post_url" type="text" name="post_url" />
    <div class="w-30"><input id="graburl" readonly="readonly" class="btn btn-outline-primary" name="submit_url" value="<?= __('app.to_extract'); ?>" /></div>
  </fieldset>
<?php endif; ?>

<label><?= __('app.content'); ?> URL<sup class="red">*</sup></label>
<textarea class="url" name="content"></textarea>
<div class="help"><?= __('app.necessarily'); ?></div>

<details class="mt15">
  <summary><?= __('app.other'); ?></summary>
  <?= insert('/content/publications/add/add-details', ['data' => $data]); ?>	
</details>

<p><?= Html::sumbit(__('app.create')); ?></p>