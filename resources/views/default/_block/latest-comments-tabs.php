<?php if (!empty($latest_comments)) : ?>
  <div class="p15">
    <div class="mb15">
      <ul class="nav small">
        <li class="tab-button active" data-id="home">
          <?= __('app.comments'); ?>
        </li>
      </ul>
    </div>
    <ul class="last-content content-tabs tab_active" id="home">
      <?php foreach ($latest_comments as $comment) : ?>
        <li>
          <div class="gray-600 flex items-center gap-min text-sm">
            <a class="flex gray-600 gap-min items-center" title="<?= $comment['login']; ?>" href="<?= url('profile', ['login' => $comment['login']]); ?>">
              <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
              <span class="nickname"><?= $comment['login']; ?></span>
            </a>
            <span class="lowercase"><?= Html::langDate($comment['comment_date']); ?></span>
          </div>
          <a class="last-content_telo" href="<?= post_slug($comment['post_id'], $comment['post_slug']); ?>#comment_<?= $comment['comment_id']; ?>">
            <?php if (mb_strlen($fragment = fragment($comment['comment_content'], 98), 'utf-8') < 5) : ?>
               <span class="lowercase">+ <?= __('app.comment'); ?>...</span>
            <?php else : ?>
               <?= $fragment; ?>
            <?php endif; ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>