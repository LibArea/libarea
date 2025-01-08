<?= insert('/_block/add-js-css'); ?>

<?php if ($container->user()->admin()) : ?>
	<main>
		<div class="box">
			<a href="/"><?= __('app.home'); ?></a> / <span class="gray-600"><?= __('app.move_comment'); ?>:</span>

			<h2 class="m0"><?= $data['post']['post_title']; ?></h2>
			<div class="label label-orange"><?= $data['comment']['comment_content'];  ?></div>

			<form class="max-w-md mb20" action="" accept-charset="UTF-8" method="post">
				<?= $container->csrf()->field(); ?>

				<b><?= __('app.being_developed'); ?>...</b>
				<h3><?= __('app.move_add_help'); ?></h3>

				<fieldset>
					<label for="post_title"><?= __('app.move_title'); ?> <sup class="red">*</sup></label>
					<input minlength="6" maxlength="250" type="text" required="" name="title">
					<div class="help">6 - 250 <?= __('app.characters'); ?></div>
				</fieldset>

				<input type="hidden" name="comment_id" value="<?= $data['comment']['comment_id']; ?>">

				<?= Html::sumbit(__('app.move')); ?>
				<a href="<?= post_slug($data['post']['post_id'], $data['post']['post_slug']); ?>#comment_<?= $data['comment']['comment_id']; ?>" class="text-sm inline ml15 gray"><?= __('app.cancel'); ?></a>
			</form>

			<h3><?= __('app.move_comment_post'); ?></h3>

			<form class="max-w-md mt20" action="" accept-charset="UTF-8" method="post">
				<?= $container->csrf()->field(); ?>

				<?= insert('/_block/form/select/move-post'); ?>
				<input type="hidden" name="comment_id" value="<?= $data['comment']['comment_id']; ?>">

				<?= Html::sumbit(__('app.move_comment')); ?>
				<a href="<?= post_slug($data['post']['post_id'], $data['post']['post_slug']); ?>#comment_<?= $data['comment']['comment_id']; ?>" class="text-sm inline ml15 gray"><?= __('app.cancel'); ?></a>
			</form>
		</div>
	</main>
<?php endif; ?>