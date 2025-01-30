<?php if (!empty($latest_comments)) : ?>
  <div class="box">
    <h4 class="uppercase-box"><?= __('app.comments'); ?></h4>
    <ul class="last-content">
      <?php foreach ($latest_comments as $comment) : ?>
        <li>
          <div class="user-info">
            <a title="<?= $comment['login']; ?>" href="<?= url('profile', ['login' => $comment['login']]); ?>">
              <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm mr5', 'small'); ?>
              <span class="nickname<?php if (Html::loginColor($comment['created_at'])) : ?> new<?php endif; ?>"><?= $comment['login']; ?></span>
            </a>
            <span class="lowercase"><?= langDate($comment['comment_date']); ?></span>
          </div>
          <a class="last-content_telo" href="<?= post_slug($comment['post_id'], $comment['post_slug']); ?>#comment_<?= $comment['comment_id']; ?>">
            <?php if (mb_strlen($fragment = fragment($comment['comment_content'], 78), 'utf-8') < 5) : ?>
              <span class="lowercase">+ <?= __('app.comment'); ?>...</span>
            <?php else : ?>
              <?= $fragment; ?>
            <?php endif; ?>
          </a>
          <div class="text-sm flex items-center">
            <svg class="icon gray-600">
              <use xlink:href="/assets/svg/icons.svg#corner-down-right"></use>
            </svg>
            <a href="<?= post_slug($comment['post_id'], $comment['post_slug']); ?>" class="black"> <?= fragment($comment['post_title'], 38); ?></a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>