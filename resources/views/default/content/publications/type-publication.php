<div class="uppercase-box">
	<?php if ($type === 'question') : ?>
	  <span class="brown"><?= __('app.question'); ?></span>
	<?php elseif ($type == 'post') : ?>
	  <span class="sky"><?= __('app.post'); ?></span>
	<?php elseif ($type == 'note') : ?>
	  <span class="red"><?= __('app.note'); ?></span>
	<?php else : ?>
	  <span class="green"><?= __('app.article'); ?></span>
	<?php endif; ?>
</div>