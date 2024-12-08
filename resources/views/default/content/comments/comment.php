<?php $n = 0;
foreach ($comments as $comment) :
  $n++; ?>
  <?php if ($n != 1) { ?><div class="br-dotted mt10 mb10"></div><?php } ?>
  <?php if ($comment['comment_published'] == 0 && $comment['comment_user_id'] != $container->user()->id() && !$container->user()->admin()) continue; ?>
  <div class="content-body">
    <div class="flex justify-between gap-min">
      <div class="flex gap-min">
        <a class="gray-600 flex gap-min" href="<?= url('profile', ['login' => $comment['login']]); ?>">
          <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
          <?= $comment['login']; ?>
        </a>
        <span class="gray-600 lowercase"><?= langDate($comment['comment_date']); ?></span>
      </div>
      <div><?= Html::votes($comment, 'comment'); ?></div>
    </div>
    <a class="block" href="<?= post_slug($comment['post_id'], $comment['post_slug']); ?>#comment_<?= $comment['comment_id']; ?>">
      <?= $comment['post_title']; ?>
    </a>
    <div class="comment-text"><?= markdown($comment['comment_content']); ?></div>
  </div>
<?php endforeach; ?>