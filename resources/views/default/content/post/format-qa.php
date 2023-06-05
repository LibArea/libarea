<?php if (!empty($data['answers'])) : ?>
  <div>
    <h2 class="lowercase m0 text-2xl">
      <?= Html::numWord($post['amount_content'], __('app.num_answer'), true); ?>
    </h2>

    <?php
    foreach ($data['answers'] as  $answer) : 
    $post_url = post_slug($post['post_id'], $post['post_slug']);
    ?>

      <?php if ($answer['answer_is_deleted'] == 1 && !UserData::checkAdmin()) continue; ?>
      <?php if ($answer['answer_published'] == 0 && $answer['answer_user_id'] != UserData::getUserId() && !UserData::checkAdmin()) continue; ?>

      <div class="block-answer br-bottom<?php if ($answer['answer_is_deleted'] == 1) : ?> m5 bg-red-200<?php endif; ?>">
      
        <?= insert('/content/post/answer-menu', ['post_url' => $post_url, 'post' => $post, 'answer' => $answer]); ?>

        <?php if ($answer['answer_lo']) : ?>
          <div title="<?= __('app.best_answer'); ?>" class="red right text-2xl p5">✓</div>
        <?php endif; ?>

        <?php if (UserData::getUserId() == $answer['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>
        <div class="br-top-dotted mb5"></div>
        <ol class="list-none">
          <li class="content_tree" id="answer_<?= $answer['answer_id']; ?>">
            <div class="max-w780">
              <?= markdown($answer['answer_content'], 'text'); ?>
            </div>
            <div class="flex text-sm justify-between">
              <div class="flex gap">
                <?= Html::votes($answer, 'answer'); ?>

                <?php if (UserData::getRegType(config('trust-levels.tl_add_comm_qa'))) : ?>
                  <?php if ($post['post_closed'] == 0 ?? $post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                    <a data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray"><?= __('app.reply'); ?></a>
                  <?php endif; ?>
                <?php endif; ?>

              </div>
              <div class="text-sm gray-600 flex gap lowercase mb10">
                <a class="brown" href="<?= url('profile', ['login' => $answer['login']]); ?>"><?= $answer['login']; ?></a>
                <span class="mb-none"><?= Html::langDate($answer['answer_date']); ?>
                  <?php if (empty($answer['edit'])) : ?>
                    (<?= __('app.ed'); ?>.)
                  <?php endif; ?></span>
                <?php if ($answer['answer_published'] == 0 && UserData::checkAdmin()) : ?>
                  <span class="ml15 red lowercase"><?= __('app.audits'); ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div data-insert="<?= $answer['answer_id']; ?>" id="insert_id_<?= $answer['answer_id']; ?>" class="none"></div>
          </li>
        </ol>
      </div>

      <ol class="list-none">
        <?php foreach ($answer['comments'] as  $comment) : ?>

          <?php if ($comment['comment_is_deleted'] == 1 && !UserData::checkAdmin()) continue; ?>
          <?php if ($comment['comment_published'] == 0 && $comment['comment_user_id'] != UserData::getUserId() && !UserData::checkAdmin()) continue; ?>

          <li class="content_tree br-li-bottom-no br-bottom ml15<?php if ($comment['comment_is_deleted'] == 1) : ?> m5 bg-red-200<?php endif; ?>" id="comment_<?= $comment['comment_id']; ?>">
            <div class="qa-comment">
              <div class="flex3 gap-min">
                <?= fragment($comment['comment_content'], 1500); ?>
                <span class="gray-600"> — </span>

                <a class="brown" href="<?= url('profile', ['login' => $comment['login']]); ?>"><?= $comment['login']; ?></a>

                <span class="lowercase gray-600 ml5 mr5"><?= Html::langDate($comment['comment_date']); ?></span>

                <?php if (UserData::getRegType(config('trust-levels.tl_add_comm_qa'))) : ?>
                  <?php if ($post['post_closed'] == 0) : ?>
                    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                      <a data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment gray-600 ml5 mr5">
                        <?= __('app.reply'); ?>
                      </a>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endif; ?>

                <?php if (Access::author('comment', $comment) === true) : ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray-600 ml5 mr5">
                    <svg class="icons">
                      <use xlink:href="/assets/svg/icons.svg#edit"></use>
                    </svg>
                  </a>
                <?php endif; ?>

                <?php if (UserData::checkAdmin()) : ?>
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray-600 ml5 mr5">
                    <svg class="icons">
                      <use xlink:href="/assets/svg/icons.svg#trash"></use>
                    </svg>
                  </a>
                <?php endif; ?>

                <?php if (UserData::getUserId() != $comment['comment_user_id'] && UserData::checkActiveUser()) : ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" data-a11y-dialog-show="my-dialog" class="gray-600 ml5 mr5">
                    <svg class="icons">
                      <use xlink:href="/assets/svg/icons.svg#alert-circle"></use>
                    </svg>
                  </a>
                <?php endif; ?>

              </div>
              <div data-insert="<?= $comment['comment_id']; ?>" id="insert_id_<?= $comment['comment_id']; ?>" class="none"></div>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>

    <?php endforeach; ?>
  </div>
<?php else : ?>
  <?php if ($post['post_closed'] != 1) : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_answers'), 'icon' => 'info']); ?>
  <?php endif; ?>
<?php endif; ?>

<?php if (!empty($otvet)) : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.you_answered'), 'icon' => 'info']); ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?php if ($post['post_feature'] == 1 && $post['post_draft'] == 0 && $post['post_closed'] == 0) : ?>

      <form class="mb15 mt20" action="<?= url('content.create', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
        <?= insert('/_block/form/editor', [
          'height'  => '250px',
          'id'      => $post['post_id'],
          'type'    => 'answer',
        ]); ?>

        <div class="clear mt5">
          <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
          <input type="hidden" name="answer_id" value="0">
          <?= Html::sumbit(__('app.reply')); ?>
        </div>
      </form>

    <?php endif; ?>
  <?php endif; ?>
<?php endif;  ?>

<?php if ($post['post_closed'] == 1) :
  echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.question_closed'), 'icon' => 'closed']);
endif; ?>