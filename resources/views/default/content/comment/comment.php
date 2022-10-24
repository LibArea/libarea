<?php $n = 0;
foreach ($comments as $comment) :
  $n++; ?>
  <?php if ($n != 1) { ?><div class="br-top-dotted mt10 mb10"></div><?php } ?>

  <?php if (!empty($comment['answer_id'])) : ?>

    <div class="flex gap-min text-sm mb5 gray-600">
      <div class="flex gap-min">
        <a class="gray flex gap-min" href="<?= url('profile', ['login' => $comment['login']]); ?>">
          <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
          <?= $comment['login']; ?>
        </a>
        <span class="lowercase"><?= Html::langDate($comment['answer_date']); ?></span> |
      </div>
      <a class="gray-600 cut-off" href="<?= url('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#answer_<?= $comment['answer_id']; ?>">
        <?= $comment['post_title']; ?>
      </a>
    </div>

    <div class="right"><?= Html::votes($comment, 'answer'); ?></div>
    <div class="cut-post"><?= markdown($comment['answer_content']); ?></div>

  <?php else : ?>

    <div class="flex gap-min text-sm mb5 gray-600">
      <div class="flex gap-min">
        <a class="gray flex gap-min" href="<?= url('profile', ['login' => $comment['login']]); ?>">
          <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
          <?= $comment['login']; ?>
        </a>
        <span class="lowercase"><?= Html::langDate($comment['comment_date']); ?></span> |
      </div>
      <a class="gray-600 cut-off" href="<?= url('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#comment_<?= $comment['comment_id']; ?>">
        <?= $comment['post_title']; ?>
      </a>
    </div>

    <div class="right"><?= Html::votes($comment, 'comment'); ?></div>
    <?= markdown($comment['comment_content']); ?>

  <?php endif; ?>

<?php endforeach; ?>