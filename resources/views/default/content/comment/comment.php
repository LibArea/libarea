<?php $n = 0;
foreach ($comments as $comment) :
  $n++; ?>
  <?php if ($n != 1) { ?><div class="br-top-dotted mt10 mb10"></div><?php } ?>

  <?php if (!empty($comment['answer_id'])) : ?>
    <?php if ($comment['answer_published'] == 0 && $comment['answer_user_id'] != UserData::getUserId() && !UserData::checkAdmin()) continue; ?>
    <div class="content-body">
      <div class="flex justify-between gap">
        <div class="flex gap">
          <a class="gray-600 flex gap-min" href="<?= url('profile', ['login' => $comment['login']]); ?>">
            <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
            <?= $comment['login']; ?>
          </a>
          <span class="gray-600 lowercase"><?= Html::langDate($comment['answer_date']); ?></span>
        </div>
        <div><?= Html::votes($comment, 'answer'); ?></div>
      </div>
      <a class="block" href="<?= post_slug($comment['post_id'], $comment['post_slug']); ?>#answer_<?= $comment['answer_id']; ?>">
        <?= $comment['post_title']; ?>
      </a>
      <div class="ind-first-p max-w780"><?= markdown($comment['answer_content']); ?></div>
    </div>
  <?php else : ?>
    <?php if ($comment['comment_published'] == 0 && $comment['comment_user_id'] != UserData::getUserId() && !UserData::checkAdmin()) continue; ?>
    <div class="content-body">
      <div class="flex justify-between gap">
        <div class="flex gap">
          <a class="gray-600 flex gap-min" href="<?= url('profile', ['login' => $comment['login']]); ?>">
            <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
            <?= $comment['login']; ?>
          </a>
          <span class="gray-600 lowercase"><?= Html::langDate($comment['comment_date']); ?></span>
        </div>
        <div><?= Html::votes($comment, 'comment'); ?></div>
      </div>
      <a class="block" href="<?= post_slug($comment['post_id'], $comment['post_slug']); ?>#comment_<?= $comment['comment_id']; ?>">
        <?= $comment['post_title']; ?>
      </a>
      <div class="ind-first-p"><?= markdown($comment['comment_content']); ?></div>
    </div>
  <?php endif; ?>

<?php endforeach; ?>