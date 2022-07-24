<?php if ($post['post_is_deleted'] == 1) : ?><span class="label-orange"><?= __('app.remote'); ?></span><?php endif; ?>
<?php if ($post['post_closed'] == 1) : ?>&#128274;<?php endif; ?>
<?php if ($post['post_top'] == 1) : ?> <span class="label-red">&#8593;</span> <?php endif; ?>
<?php if ($post['post_lo']) : ?><span class="red">✓</span><?php endif; ?>
<?php if ($post['post_feature'] == 1) : ?> <span class="label-green"><?= __('app.question'); ?></span> <?php endif; ?>
<?php if ($post['post_translation'] == 1) : ?><span class="label-grey"><?= __('app.translation'); ?></span><?php endif; ?>
<?php if ($post['post_tl']) : ?> <span class="brown italic text-sm ml5">tl<?= $post['post_tl']; ?></span> <?php endif; ?>
<?php if ($post['post_merged_id']) : ?>
  <span class="red">
    <svg class="icons">
      <use xlink:href="/assets/svg/icons.svg#git-merge"></use>
    </svg>
    <?php if (UserData::checkAdmin()) : ?>
      <a href="/post/<?= $post['post_merged_id']; ?>">id <?= $post['post_merged_id']; ?></a>
    <?php endif; ?>
  </span>
<?php endif; ?>