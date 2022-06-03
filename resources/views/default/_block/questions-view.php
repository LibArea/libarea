<?php if (!empty($data['answers'])) : ?>
  <div class="box">
    <h2 class="lowercase m0 text-2xl">
      <?= Html::numWord($post['amount_content'], __('app.num_answer'), true); ?>
    </h2>

    <?php foreach ($data['answers'] as  $answer) : ?>
      <div class="block-answer mb15">
        <?php if ($answer['answer_is_deleted'] == 0) : ?>
          <?php if (UserData::getUserId() == $answer['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>
          <div class="br-top-dotted mb20"></div>
          <ol class="list-none">
            <li class="content_tree" id="answer_<?= $answer['answer_id']; ?>">
              <div class="hidden">
                <div class="br-gray w94 box right hidden center">
                  <?= Html::image($answer['avatar'], $answer['login'], 'img-lg', 'avatar', 'max'); ?>
                  <div class="text-sm gray">
                    <?= Html::langDate($answer['date']); ?>
                    <?php if (empty($answer['edit'])) : ?>
                      (<?= __('app.ed'); ?>.)
                    <?php endif; ?>
                    <?= insert('/_block/admin-show-ip', ['ip' => $answer['answer_ip'], 'publ' => $answer['answer_published']]); ?>
                  </div>
                  <a class="qa-login" href="<?= url('profile', ['login' => $answer['login']]); ?>"><?= $answer['login']; ?></a>
                </div>
                <div class="max-w780">
                  <?= Content::text($answer['answer_content'], 'text'); ?>
                </DIV>
              </div>
              <div class="flex text-sm gap">
                <?= Html::votes($answer, 'answer', 'ps', 'bi-heart'); ?>
 
                <?php if (UserData::getRegType(config('trust-levels.tl_add_comm_qa'))) : ?>
                  <?php if ($post['post_closed'] == 0) : ?>
                    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                      <a data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray"><?= __('app.reply'); ?></a>
                    <?php endif; ?>
                  <?php endif; ?>
                <?php endif; ?>

                <?php if (Access::author('answer', $answer['answer_user_id'], $answer['date'], 30) === true) : ?>
                  <?php if (UserData::getUserId() == $answer['answer_user_id'] || UserData::checkAdmin()) : ?>
                    <a class="editansw gray" href="<?= url('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]); ?>">
                      <?= __('app.edit'); ?>
                    </a>
                  <?php endif; ?>
                <?php endif; ?>

                <?php if (UserData::checkAdmin()) : ?>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray">
                    <i title="<?= __('app.remove'); ?>" class="bi-trash"></i>
                  </a>
                <?php endif; ?>

                <?= Html::favorite($answer['answer_id'], 'answer', $answer['tid'], 'ps'); ?>
 
                <?php if (UserData::getUserId() != $answer['answer_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray-600">
                    <i title="<?= __('app.report'); ?>" class="bi-flag"></i>
                  </a>
                <?php endif; ?>
              </div>
              <div data-insert="<?= $answer['answer_id']; ?>" id="insert_id_<?= $answer['answer_id']; ?>" class="none"></div>
            </li>
          </ol>
        <?php endif; ?>
      </div>

      <?php $n = 0;
      foreach ($answer['comments'] as  $comment) :
        $n++; ?>
        <?php if ($comment['comment_is_deleted'] == 0) : ?>
          <div class="br-bottom<?php if ($n > 1) : ?> ml30<?php endif; ?>"></div>
          <ol class="max-w780 list-none mb0 mt0">
            <li class="content_tree" id="comment_<?= $comment['comment_id']; ?>">
              <div class="text-sm ml30">
                <?= Content::text($comment['comment_content'], 'line'); ?>
                <div class="text-sm flex gap">
                  <span class="gray-600">
                    <a class="gray-600" href="<?= url('profile', ['login' => $comment['login']]); ?>"><?= $comment['login']; ?></a>
                    <span class="lowercase gray-600">
                      &nbsp; <?= Html::langDate($comment['date']); ?>
                    </span>
                  </span>

                  <?php if (UserData::getRegType(config('trust-levels.tl_add_comm_qa'))) : ?>
                    <?php if ($post['post_closed'] == 0) : ?>
                      <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                        <a data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment lowercase gray-600">
                          <?= __('app.reply'); ?>
                        </a>
                      <?php endif; ?>
                    <?php endif; ?>
                  <?php endif; ?>

                  <?php if (Access::author('comment', $comment['comment_user_id'], $comment['date'], 30) === true) : ?>
                      <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray-600">
                        <i title="<?= __('app.edit'); ?>" class="bi-pencil-square"></i>
                      </a>
                  <?php endif; ?>

                  <?php if (UserData::checkAdmin()) : ?>
                    <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray-600">
                      <i title="<?= __('app.remove'); ?>" class="bi-trash"></i>
                    </a>
                  <?php endif; ?>

                  <?php if (UserData::getUserId() != $comment['comment_user_id'] && UserData::checkActiveUser()) : ?>
                    <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray-600">
                      <?= __('app.report'); ?>
                    </a>
                  <?php endif; ?>
                  <?= insert('/_block/admin-show-ip', ['ip' => $comment['comment_ip'], 'publ' => $comment['comment_published']]); ?>
                </div>
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
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_answers'), 'icon' => 'bi-info-lg']); ?>
  <?php endif; ?>
<?php endif; ?>

<?php if (!empty($otvet)) : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.you_answered'), 'icon' => 'bi-info-lg']); ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?php if ($post['post_feature'] == 1 && $post['post_draft'] == 0 && $post['post_closed'] == 0) : ?>

      <form class="mb15" action="<?= url('content.create', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
        <?= insert('/_block/form/editor', [
          'height'  => '250px',
          'id'      => $post['post_id'],
          'type'    => 'answer',
        ]); ?>

        <div class="clear pt5">
          <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
          <input type="hidden" name="answer_id" value="0">
          <?= Html::sumbit(__('app.reply')); ?>
        </div>
      </form>

    <?php endif; ?>
  <?php endif; ?>
<?php endif;  ?>