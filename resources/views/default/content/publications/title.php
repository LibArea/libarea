<?php if ($item['post_is_deleted'] == 1) : ?><span class="label label-orange"><?= __('app.remote'); ?></span><?php endif; ?>
<?php if ($item['post_closed'] == 1) : ?><span class="gray-600" title="<?= __('app.close'); ?>"><?= icon('icons', 'lock'); ?></span><?php endif; ?>
<?php if ($item['post_top'] == 1) : ?> <sup class="red"><?= icon('icons', 'pin'); ?></sup> <?php endif; ?>
<?php if ($item['post_lo']) : ?><span class="red"><?= icon('icons', 'selected'); ?></span><?php endif; ?>
<?php if ($item['post_translation'] == 1) : ?><span class="label label-grey"><?= __('app.translation'); ?></span><?php endif; ?>
<?php if ($item['post_tl']) : ?> <span class="brown italic text-sm ml5">tl<?= $item['post_tl']; ?></span> <?php endif; ?>
<?php if ($item['post_nsfw']) : ?> <span class="red text-sm ml5">NSFW</span> <?php endif; ?>
<?php if ($item['post_hidden']) : ?> <?= icon('icons', 'eye'); ?> <?php endif; ?>
<?php if ($item['post_published'] == 0 && $container->user()->admin()) : ?> <sup class="red lowercase"><?= __('app.audits'); ?></sub><?php endif; ?>
<?php if ($item['post_merged_id']) : ?>
  <span class="red">
	<?= icon('icons', 'chevron-right'); ?>
    <?php if ($container->user()->admin()) : ?>
      <a href="<?= url('post.id', ['id' => $item['post_merged_id']]); ?>"> id <?= $item['post_merged_id']; ?></a>
    <?php endif; ?>
  </span>
<?php endif; ?>