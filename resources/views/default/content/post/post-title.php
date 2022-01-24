<?= $post['post_title']; ?>

<?php if ($post['post_is_deleted'] == 1) { ?><i class="bi bi-trash red-500"></i><?php } ?>
<?php if ($post['post_closed'] == 1) { ?><i class="bi bi-lock gray"></i><?php } ?>
<?php if ($post['post_top'] == 1) { ?><i class="bi bi-pin-angle sky-500"></i><?php } ?>
<?php if ($post['post_lo'] > 0) { ?><i class="bi bi-award sky-500"></i><?php } ?>
<?php if ($post['post_feature'] == 1) { ?><i class="bi bi-patch-question green-600"></i><?php } ?>
<?php if ($post['post_translation'] == 1) { ?>
<?php if ($post['post_merged_id'] > 0) { ?><i class="bi bi-link-45deg sky-500"></i><?php } ?>

<span class="pt5 pr10 pb5 pl10 gray-600 bg-yellow-100 br-rd3 text-sm italic lowercase">
  <?= Translate::get('translation'); ?>
</span>
<?php } ?>

<?php if ($post['post_tl'] > 0) { ?>
<span class="pt5 pr10 pb5 pl10 gray-600 bg-orange-100 br-rd3 italic text-sm">
  tl<?= $post['post_tl']; ?>
</span>
<?php } ?>
 

