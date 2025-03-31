<?php foreach ($comments as $comment) : ?>
  <?php if ($comment['comment_published'] == 0 && $comment['comment_user_id'] != $container->user()->id() && !$container->user()->admin()) continue; ?>
  <div class="box">
    <?= insert('/content/publications/type-publication', ['type' => $comment['post_type']]); ?>
  
    <div class="comment-body">
      <div class="flex justify-between">
        <div class="user-info">
          <a href="<?= url('profile', ['login' => $comment['login']]); ?>">
            <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
            <span class="nickname"><?= $comment['login']; ?></span>
          </a>
          <span class="lowercase"><?= langDate($comment['comment_date']); ?></span>
        </div>
        <?= Html::votes($comment, 'comment'); ?>
      </div>
      <a class="block" href="<?= post_slug($comment['post_type'], $comment['post_id'], $comment['post_slug']); ?>#comment_<?= $comment['comment_id']; ?>">
	    <?php if ($comment['post_type'] === 'post') : ?>
	      <div class="comment-text black"><?= markdown($comment['comment_content']); ?></div>
	    <?php else : ?>
          <?= $comment['post_title']; ?>
		<?php endif; ?>
      </a>
	  <?php if ($comment['post_type'] != 'post') : ?>
		<div class="comment-text"><?= markdown($comment['comment_content']); ?></div>
	  <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>