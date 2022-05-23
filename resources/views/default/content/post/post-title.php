<?php if ($post['post_is_deleted'] == 1) : ?><i class="bi-trash red"></i><?php endif; ?>
<?php if ($post['post_closed'] == 1) : ?><i class="bi-lock gray-600 ml5"></i><?php endif; ?>
<?php if ($post['post_top'] == 1) : ?><i class="bi-pin-angle sky ml5"></i><?php endif; ?>
<?php if ($post['post_lo']) : ?><i class="bi-award sky"></i><?php endif; ?>
<?php if ($post['post_feature'] == 1) : ?><i class="bi-patch-question green ml5"></i><?php endif; ?>
<?php if ($post['post_translation'] == 1) : ?>
  <?php if ($post['post_merged_id']) : ?><i class="bi-link-45deg sky ml5"></i><?php endif; ?>
  <span class="pt5 pr10 pb5 pl10 gray-600 bg-violet br-rd3 text-sm italic lowercase"><?= __('app.translation'); ?></span>
<?php endif; ?>
<?php if ($post['post_tl']) : ?><span class="green italic text-sm ml5">tl<?= $post['post_tl']; ?></span><?php endif; ?>