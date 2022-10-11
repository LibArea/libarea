<?php $n = 0;
foreach ($comments as $comment) :
  $n++; ?>
  <?php if ($n != 1) { ?><div class="br-top-dotted mt10 mb10"></div><?php } ?>

  <?php if (!empty($comment['answer_id'])) : ?>

    <div class="flex gap-min items-center text-sm mb5 gray-600">
      <a class="gray-600 flex items-center" href="<?= url('profile', ['login' => $comment['login']]); ?>">
        <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm mr5', 'small'); ?>
        <?= $comment['login']; ?>
      </a>
      <span class="lowercase"><?= Html::langDate($comment['answer_date']); ?></span> |
      <span class="lowercase"><?= __('app.in'); ?>:</span>
      <a class="gray-600" href="<?= url('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#answer_<?= $comment['answer_id']; ?>">
        <?= $comment['post_title']; ?>
      </a>
    </div>
    <?= markdown($comment['answer_content']); ?>

  <?php else : ?>

    <div class="flex gap-min items-center text-sm mb5 gray-600">
      <a class="gray-600 flex items-center" href="<?= url('profile', ['login' => $comment['login']]); ?>">
        <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm mr5', 'small'); ?>
        <?= $comment['login']; ?>
      </a>
      <span class="lowercase"><?= Html::langDate($comment['comment_date']); ?></span> |
      <span class="lowercase"><?= __('app.in'); ?>:</span>
      <a class="gray-600" href="<?= url('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#comment_<?= $comment['comment_id']; ?>">
        <?= $comment['post_title']; ?>
      </a>
    </div>
    <?= markdown($comment['comment_content']); ?>

  <?php endif; ?>

<?php endforeach; ?>