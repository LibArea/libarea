  <div class="menu-comment">
    <span class="trigger gray-600 text-sm">
	  <?= icon('icons', 'more-horizontal'); ?>
    </span>
    <ul class="dropdown text-sm">

      <?php if ($container->access()->author('comment', $comment) === true) : ?>
        <li>
          <a class="editansw" href="<?= url('comment.form.edit', ['id' => $comment['comment_id']]); ?>">
		    <?= icon('icons', 'edit'); ?>
            <?= __('app.edit'); ?>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($container->user()->admin()) : ?>
        <li>
          <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action">
			<?= icon('icons', 'trash-2'); ?>
            <?php if ($comment['comment_is_deleted'] == 1) : ?>
              <span class="sky"><?= __('app.recover'); ?></span>
            <?php else : ?>
              <?= __('app.remove'); ?>
            <?php endif; ?>
          </a>
        </li>
        <!--li>
          <a class="editansw" href="<?= url('admin.comment.transfer.form.edit', ['id' => $comment['comment_id']]); ?>">
            ...
            <?= __('app.move'); ?>
          </a>
        </li-->
        <li>
          <a href="<?= url('admin.logip', ['item' => $comment['comment_ip']]); ?>">
		    <?= icon('icons', 'info'); ?>
            <?= $comment['comment_ip']; ?>
          </a>
        <li>
        <?php endif; ?>

        <?php if ($container->user()->id() != $comment['comment_user_id'] && $container->access()->limitTl(config('trust-levels', 'tl_add_report'))) : ?>
        <li>
          <a data-post_id="<?= $item['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" data-a11y-dialog-show="my-dialog">
		    <?= icon('icons', 'x-octagon'); ?>
            <?= __('app.report'); ?>
          </a>
        </li>
      <?php endif; ?>

      <?php if ($type == 'qa') : ?>
        <?php if ($item['post_comments_count'] > 1 && $level == '0_0') : ?>
          <?php if ($container->user()->id() == $item['post_user_id'] || $container->user()->admin()) : ?>
            <li>
              <a id="best_<?= $comment['comment_id']; ?>" data-id="<?= $comment['comment_id']; ?>" class="comment-best">
			    <?= icon('icons', 'award'); ?>
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
        <a rel="nofollow" class="gray-600" href="<?= post_slug($item['post_type'], $item['post_id'], $item['post_slug']); ?>#comment_<?= $comment['comment_id']; ?>">
		  <?= icon('icons', 'anchor'); ?>
          <?= __('app.link'); ?>
        </a>
      </li>

    </ul>
  </div>