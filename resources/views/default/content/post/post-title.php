<?php if ($post['post_is_deleted'] == 1) { ?><i class="bi-trash red"></i><?php } ?>
<?php if ($post['post_closed'] == 1) { ?><i class="bi-lock gray"></i><?php } ?>
<?php if ($post['post_top'] == 1) { ?><i class="bi-pin-angle sky-500"></i><?php } ?>
<?php if ($post['post_lo'] > 0) { ?><i class="bi-award sky-500"></i><?php } ?>
<?php if ($post['post_feature'] == 1) { ?><i class="bi-patch-question green"></i><?php } ?>
<?php if ($post['post_translation'] == 1) { ?>
  <?php if ($post['post_merged_id'] > 0) { ?><i class="bi-link-45deg sky-500"></i><?php } ?>
  <span class="pt5 pr10 pb5 pl10 gray-600 bg-violet-50 br-rd3 text-sm italic lowercase"><?= Translate::get('translation'); ?></span>
<?php } ?>
<?php if ($post['post_tl'] > 0) { ?><span class="yellow italic text-sm">tl<?= $post['post_tl']; ?></span><?php } ?>