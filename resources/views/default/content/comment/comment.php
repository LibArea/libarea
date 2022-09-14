<?php $n = 0;
foreach ($answer as  $comment) :
  $n++; ?>
  <?php if ($n != 1) { ?><div class="br-top-dotted mt10 mb10"></div><?php } ?>
  <?php if ($comment['comment_is_deleted'] == 1) : ?>
    <?php if (Access::author('comment', $comment, 30) === true) : ?>
      <ol class="bg-red-200 text-sm list-none max-w780">
        <li class="pr5" id="comment_<?= $comment['comment_id']; ?>">
          <span class="comm-deletes gray">
            <?= Content::text($comment['content'], 'text'); ?>
            — <?= $comment['login']; ?>
            <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action right text-sm">
              <?= __('app.recover'); ?>
            </a>
          </span>
        </li>
      </ol>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($comment['comment_is_deleted'] == 0) : ?>
    <ol class="list-none box box-fon">
      <li class="content_tree" id="comment_<?= $comment['comment_id']; ?>">
        <div class="max-w780">
          <div class="text-sm flex gap">
            <a class="gray-600 flex " href="<?= url('profile', ['login' => $comment['login']]); ?>">
              <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm mr5', 'small'); ?>
              <?= $comment['login']; ?>
            </a>
            <?php if ($comment['post_user_id'] == $comment['comment_user_id']) : ?>
              <svg class="icons sky">
                <use xlink:href="/assets/svg/icons.svg#mic"></use>
              </svg>
            <?php endif; ?>
            <span class="gray-600 lowercase">
              <?= Html::langDate($comment['comment_date']); ?>
            </span>
            <?= insert('/_block/admin-show-ip', ['ip' => $comment['comment_ip'], 'publ' => $comment['comment_published']]); ?>
          </div>
          <a href="<?= url('post', ['id' => $comment['post_id'], 'slug' => $comment['post_slug']]); ?>#comment_<?= $comment['comment_id']; ?>">
            <?= $comment['post_title']; ?>
          </a>
          <div class="content-body">
            <?= Content::text($comment['content'], 'text'); ?>
          </div>
        </div>
        <div class="text-sm flex gap">
          <?= Html::votes($comment, 'comment'); ?>

          <?php if (Access::author('comment', $comment, 30) === true) : ?>
            <a data-post_id="<?= $comment['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray-600">
              <?= __('app.edit'); ?>
            </a>
            <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray-600">
              <?= __('app.remove'); ?>
            </a>
          <?php endif; ?>

          <?php if (UserData::getUserId() != $comment['comment_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
            <a data-post_id="<?= $comment['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray-600">
              <svg class="icons">
                <use xlink:href="/assets/svg/icons.svg#flag"></use>
              </svg>
            </a>
          <?php endif; ?>
        </div>
        <div data-insert="<?= $comment['comment_id']; ?>" id="insert_id_<?= $comment['comment_id']; ?>" class="none"></div>
      </li>
    </ol>
  <?php endif; ?>
<?php endforeach; ?>