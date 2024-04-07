<?= insert('/_block/add-js-css'); ?>

<?php if ($container->user()->admin()) : ?>
<main>
  <div class="indent-body">
	  <a href="/"><?= __('app.home'); ?></a> / <span class="gray-600"><?= __('app.move_comment'); ?>:</span>
	  
      <div class="label label-orange mb20"><?= $data['comment']['comment_content'];  ?></div>
   
	  <form class="max-w780" action="" accept-charset="UTF-8" method="post">
		<?= $container->csrf()->field(); ?>

		<b><?= __('app.being_developed'); ?>...</b>
		<div class="mb20"><?= __('app.move_add_help'); ?></div>

		<fieldset>
		  <label for="post_title"><?= __('app.move_title'); ?> <sup class="red">*</sup></label>
		  <input minlength="6" maxlength="250" type="text" required="" name="title">
		  <div class="help">6 - 250 <?= __('app.characters'); ?></div>
		</fieldset>

		<input type="hidden" name="comment_id" value="<?= $data['comment']['comment_id']; ?>">
		
		<?= Html::sumbit(__('app.edit')); ?>
		<a href="<?= post_slug($data['post']['post_id'], $data['post']['post_slug']); ?>#comment_<?= $data['comment']['comment_id']; ?>" class="text-sm inline ml15 gray"><?= __('app.cancel'); ?></a>
		
	  </form>
  </div>
</main>
<?php endif; ?>