<?php if (!empty($data['answers'])) : ?>
  <div>
    <h2 class="lowercase mb15 text-2xl">
      <?= Html::numWord($post['amount_content'], __('app.num_answer'), true); ?>
    </h2>
    <?php $n = 0;
    foreach ($data['answers'] as  $answer) :
      $n++;
      $post_url = url('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
    ?>

      <div class="block-answer mb15">
        <?php if ($answer['answer_is_deleted'] == 0) : ?>
          <?php if ($n != 1) : ?><div class="br-top-dotted mt10 mb10"></div><?php endif; ?>
          <ol class="list-none">
            <li class="content_tree" id="answer_<?= $answer['answer_id']; ?>">
              <div class="content-body">
                <div class="flex text-sm gap">
                  <a class="gray-600" href="<?= url('profile', ['login' => $answer['login']]); ?>">
                    <?= Img::avatar($answer['avatar'], $answer['login'], 'img-sm mr5', 'small'); ?>
                    <span <?php if (Html::loginColor($answer['created_at'])) : ?> class="green" <?php endif; ?>>
                      <?= $answer['login']; ?>
                    </span>
                  </a>
                  <?php if ($post['post_user_id'] == $answer['answer_user_id']) : ?>
                    <svg class="icons icon-small sky">
                      <use xlink:href="/assets/svg/icons.svg#mic"></use>
                    </svg>
                  <?php endif; ?>
                  <span class="gray-600 lowercase">
                    <?= Html::langDate($answer['answer_date']); ?>
                  </span>
                  <?php if (empty($answer['edit'])) : ?>
                    <span class="gray-600">
                      (<?= __('app.ed'); ?>.)
                    </span>
                  <?php endif; ?>
                  <a rel="nofollow" class="gray-600" href="<?= $post_url; ?>#answer_<?= $answer['answer_id']; ?>"><svg class="icons icon-small">
                      <use xlink:href="/assets/svg/icons.svg#anchor"></use>
                    </svg></a>
                  <?= insert('/_block/admin-show-ip', ['ip' => $answer['answer_ip'], 'publ' => $answer['answer_published']]); ?>
                </div>
                <div class="max-w780 ind-first-p">
                  <?= markdown($answer['answer_content'], 'text'); ?>
                </div>
              </div>
              <div class="flex text-sm gap">
                <?= Html::votes($answer, 'answer'); ?>

                <?php if ($post['post_closed'] == 0) : ?>
                  <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                    <a data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray-600"><?= __('app.reply'); ?></a>
                  <?php endif; ?>
                <?php endif; ?>

                <?php if (Access::author('answer', $answer, 30) === true) : ?>
                  <a class="editansw gray-600" href="<?= url('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]); ?>">
                    <?= __('app.edit'); ?>
                  </a>
                <?php endif; ?>

                <?php if (UserData::checkAdmin()) : ?>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray-600">
                    <svg class="icons">
                      <use xlink:href="/assets/svg/icons.svg#trash"></use>
                    </svg>
                  </a>
                <?php endif; ?>

                <?= Html::favorite($answer['answer_id'], 'answer', $answer['tid']); ?>

                <?php if (UserData::getUserId() != $answer['answer_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray-600">
                    <svg class="icons">
                      <use xlink:href="/assets/svg/icons.svg#flag"></use>
                    </svg>
                  </a>
                <?php endif; ?>
              </div>
              <div data-insert="<?= $answer['answer_id']; ?>" id="insert_id_<?= $answer['answer_id']; ?>" class="none"></div>
            </li>
          </ol>

        <?php else : ?>

          <?php if (UserData::checkAdmin()) : ?>
            <ol class="bg-red-200 text-sm pr5 list-none">
              <li class="content_tree" id="comment_<?= $answer['answer_id']; ?>">
                <span class="comm-deletes nick">
                  <?= markdown($answer['answer_content'], 'text'); ?>
                  <?= __('app.answer'); ?> —
                  <span class="u<?php if (Html::loginColor($answer['created_at'])) : ?> green<?php endif; ?>">
                    <?= $answer['login']; ?>
                  </span>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action right">
                    <?= __('app.remove'); ?>
                  </a>
                </span>
              </li>
            </ol>
          <?php else : ?>
            <div class="gray-600 p15 text-sm">
              ~ <?= __('app.content_deleted', ['name' => __('app.answer')]); ?>...
            </div>
          <?php endif; ?>

        <?php endif; ?>
      </div>

      <?php foreach ($answer['comments'] as  $comment) : ?>

        <?php if ($comment['comment_is_deleted'] == 1) : ?>
          <?php if (Access::author('comment', $comment, 30) === true) : ?>
            <ol class="bg-red-200 text-sm list-none max-w780 <?php if ($comment['comment_comment_id'] > 0) : ?> ml30<?php endif; ?>">
              <li class="pr5" id="comment_<?= $comment['comment_id']; ?>">
                <span class="comm-deletes gray">
                  <?= markdown($comment['comment_content'], 'line'); ?>
                  —
                  <span class="u<?php if (Html::loginColor($comment['created_at'])) : ?> green<?php endif; ?>">
                    <?= $comment['login']; ?>
                  </span>
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action right text-sm">
                    <?= __('app.recover'); ?>
                  </a>
                </span>
              </li>
            </ol>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($comment['comment_is_deleted'] == 0) : ?>
          <ol class="list-none">
            <li class="content_tree mb20 ml15<?php if ($comment['comment_comment_id'] > 0) : ?> ml30<?php endif; ?>" id="comment_<?= $comment['comment_id']; ?>">
              <div class="text-sm flex gap">
                <a class="gray-600" href="<?= url('profile', ['login' => $comment['login']]); ?>">
                  <?= Img::avatar($comment['avatar'], $comment['login'], 'img-sm', 'small'); ?>
                  <span class="<?php if (Html::loginColor($comment['created_at'])) : ?> green<?php endif; ?>">
                    <?= $comment['login']; ?>
                  </span>
                </a>
                <?php if ($post['post_user_id'] == $comment['comment_user_id']) : ?>
                  <svg class="icons icon-small sky">
                    <use xlink:href="/assets/svg/icons.svg#mic"></use>
                  </svg>
                <?php endif; ?>
                <span class="gray-600 lowercase">
                  <?= Html::langDate($comment['comment_date']); ?>
                </span>
                <?php if ($comment['comment_comment_id'] > 0) : ?>
                  <a class="gray-600" rel="nofollow" href="<?= $post_url; ?>#comment_<?= $comment['comment_comment_id']; ?>"><svg class="icons icon-small">
                      <use xlink:href="/assets/svg/icons.svg#arrow-up"></use>
                    </svg></a>
                <?php else : ?>
                  <a class="gray-600" rel="nofollow" href="<?= $post_url; ?>#answer_<?= $comment['comment_answer_id']; ?>"><svg class="icons icon-small">
                      <use xlink:href="/assets/svg/icons.svg#arrow-up"></use>
                    </svg></a>
                <?php endif; ?>
                <a class="gray-600" rel="nofollow" href="<?= $post_url; ?>#comment_<?= $comment['comment_id']; ?>"><svg class="icons icon-small">
                    <use xlink:href="/assets/svg/icons.svg#anchor"></use>
                  </svg></a>
                <?= insert('/_block/admin-show-ip', ['ip' => $comment['comment_ip'], 'publ' => $comment['comment_published']]); ?>
              </div>
              <div class="max-w780 ind-first-p">
                <?= markdown($comment['comment_content'], 'text'); ?>
              </div>
              <div class="text-sm flex gap">
                <?= Html::votes($comment, 'comment'); ?>

                <?php if ($post['post_closed'] == 0) : ?>
                  <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                    <a data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment gray">
                      <?= __('app.reply'); ?>
                    </a>
                  <?php endif; ?>
                <?php endif; ?>

                <?php if (Access::author('comment', $comment, 30) === true) : ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray">
                    <?= __('app.edit'); ?> 
                  </a>
                <?php endif; ?>
                <?php if (UserData::checkAdmin()) : ?>
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray">
                    <?= __('app.remove'); ?>
                  </a>
                <?php endif; ?>

                <?php if (UserData::getUserId() != $comment['comment_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray-600">
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
    <?php endforeach; ?>
  </div>
<?php else : ?>
  <?php if ($post['post_closed'] != 1) : ?>
    <?php if (UserData::checkActiveUser()) : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_comments'), 'icon' => 'info']); ?>
    <?php else : ?>
      <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_auth'), 'icon' => 'info']); ?>
    <?php endif; ?>
  <?php endif; ?>
<?php endif; ?>

<?php if ($post['post_closed'] == 1) :
  echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.close'), 'icon' => 'closed']);
endif; ?>