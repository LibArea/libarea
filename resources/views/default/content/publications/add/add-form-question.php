<?= insert('/_block/form/select/topic', ['topic'  => $data['topic'], 'action' => 'add']); ?>

<?php if (!empty($data['showing-blog'])) : ?>
	<?= insert('/_block/form/select/blog', [
		'blog'       => $data['blog'],
		'action'  => 'add',
		'title'    => __('app.blogs'),
	]); ?>
<?php endif; ?>

<fieldset class="form-big">
	<div class="form-label input-label"><label><?= __('app.heading'); ?></label></div>
	<div class="form-element">
		<input id="title" type="text" required="" name="title">
		<div class="help">6 - 250 <?= __('app.characters'); ?></div>
	</div>
</fieldset>

<fieldset>
	<div class="form-label input-label"><label><?= __('app.text'); ?> Q&A</label></div>
	<div class="form-element">
		<textarea rows="5" cols="33" name="content"></textarea>
		<div class="help"><?= __('app.necessarily'); ?></div>
	</div>
</fieldset>

<details class="mt15">
	<summary><?= __('app.other'); ?></summary>
	<?= insert('/content/publications/add/add-details', ['data' => $data]); ?>
</details>