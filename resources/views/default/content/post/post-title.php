<?php if ($post['post_is_deleted'] == 1) : ?><span class="label-orange"><?= __('app.remote'); ?></span><?php endif; ?>
<?php if ($post['post_closed'] == 1) : ?>&#128274;<?php endif; ?>
<?php if ($post['post_top'] == 1) : ?> <span class="label-red">&#8593;</span> <?php endif; ?>
<?php if ($post['post_lo']) : ?><span class="red">✓</span><?php endif; ?>
<?php if ($post['post_feature'] == 1) : ?> <span class="label-green"><?= __('app.question'); ?></span> <?php endif; ?>
<?php if ($post['post_translation'] == 1) : ?>
  <?php if ($post['post_merged_id']) : ?> <span class="pink">&#8663;</span> <?php endif; ?>
  <span class="label-grey"><?= __('app.translation'); ?></span>
<?php endif; ?>
<?php if ($post['post_tl']) : ?> <span class="brown italic text-sm ml5">tl<?= $post['post_tl']; ?></span> <?php endif; ?>