<?php if ($data['count_posts'] + $data['count_answers'] + $data['count_comments'] > 0) : ?>
    <div class="delet-count">
      <?= __('app.deleted'); ?>:

      <?php if ($data['count_posts'] > 0) : ?>
        <span><?= __('app.posts'); ?> <?= $data['count_posts']; ?></span>
      <?php endif; ?>
      
      <?php if ($data['count_answers'] > 0) : ?>
        <span><?= __('app.answers'); ?> <?= $data['count_answers']; ?></span>
      <?php endif; ?>  
      
      <?php if ($data['count_comments'] > 0) : ?>
        <span><?= __('app.comments'); ?> <?= $data['count_comments']; ?></span>
      <?php endif; ?>
    </div>
<?php endif; ?>