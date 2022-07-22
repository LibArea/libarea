<?php if ($count['count_posts'] + $count['count_answers'] + $count['count_comments'] > 0) : ?>
    <div class="delet-count">
      <?= __('app.deleted'); ?>:

      <?php if ($count['count_posts'] > 0) : ?>
        <span><?= __('app.posts'); ?> <?= $count['count_posts']; ?></span>
      <?php endif; ?>
      
      <?php if ($count['count_answers'] > 0) : ?>
        <span><?= __('app.answers'); ?> <?= $count['count_answers']; ?></span>
      <?php endif; ?>  
      
      <?php if ($count['count_comments'] > 0) : ?>
        <span><?= __('app.comments'); ?> <?= $count['count_comments']; ?></span>
      <?php endif; ?>
    </div>
<?php endif; ?>