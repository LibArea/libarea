<div class="right inline">
  <div class="relative ml10">
    <span class="trigger gray-600 text-sm">
      <svg class="icons">
        <use xlink:href="/assets/svg/icons.svg#more-horizontal"></use>
      </svg>
    </span>
    <ul class="dropdown">

      <?php if (Access::author('comment', $comment) === true) : ?>
        <li>
          <a class="editansw" href="<?= url('content.edit', ['type' => 'comment', 'id' => $comment['comment_id']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#edit"></use>
            </svg>
            <?= __('app.edit'); ?>
          </a>
        </li>
      <?php endif; ?>

      <?php if (UserData::checkAdmin()) : ?>
        <li>
          <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#trash-2"></use>
            </svg>
            <?= $comment['comment_is_deleted'] == 1 ? __('app.recover') : __('app.remove'); ?>
          </a>
        </li>

        <li>
          <a href="<?= url('admin.logip', ['ip' => $comment['comment_ip']]); ?>">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#info"></use>
            </svg>
            <?= $comment['comment_ip']; ?>
          </a>
        <li>
        <?php endif; ?>

        <?php if (UserData::getUserId() != $comment['comment_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
        <li>
          <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" data-a11y-dialog-show="my-dialog">
            <svg class="icons">
              <use xlink:href="/assets/svg/icons.svg#alert-circle"></use>
            </svg>
            <?= __('app.report'); ?>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($type == 'qa') : ?>
        <?php if ($post['post_comments_count'] > 1 && $level == 0) : ?>
          <?php if (UserData::getUserId() == $post['post_user_id'] || UserData::checkAdmin()) : ?>
            <li>
              <a id="best_<?= $comment['comment_id']; ?>" data-id="<?= $comment['comment_id']; ?>" class="comment-best">
                <svg class="icons">
                  <use xlink:href="/assets/svg/icons.svg#award"></use>
                </svg>
                <?= __('app.raise_answer'); ?>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>

        <li>
          <?= Html::favorite($comment['comment_id'], 'comment', $comment['tid'], 'heading'); ?>
        </li>
      <?php endif; ?>

      <li>
        <a rel="nofollow" class="gray-600" href="<?= post_slug($post['post_id'], $post['post_slug']); ?>#comment_<?= $comment['comment_id']; ?>">
          <svg class="icons">
            <use xlink:href="/assets/svg/icons.svg#anchor"></use>
          </svg>
          <?= __('app.link'); ?>
        </a>
      </li>

    </ul>
  </div>
</div>