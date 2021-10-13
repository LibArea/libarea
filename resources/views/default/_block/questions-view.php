<?php if (!empty($data['answers'])) { ?>
  <div class="bg-white br-rd-5 border-box-1 p15">
    <h2 class="lowercase m0 size-21">
      <?= $data['post']['post_answers_count'] ?> <?= $data['post']['num_answers'] ?>
    </h2>

    <?php foreach ($data['answers'] as  $answer) { ?>
      <div class="block-answer">
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <?php if ($uid['user_id'] == $answer['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>
          <div class="line mb20"></div>
          <ol class="p0 m0 list-none">
            <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
              <div class="answ-telo hidden">
                <div class="border-box-1 w130 br-rd-3 right mt10 pt10 ml10 pb10 hidden center">
                  <?= user_avatar_img($answer['user_avatar'], 'max', $answer['user_login'], 'br-rd-50 w64'); ?>
                  <div class="size-14 gray-light">
                    <?= $answer['answer_date']; ?>
                    <?php if (empty($answer['edit'])) { ?>
                      (<?= lang('ed'); ?>.)
                    <?php } ?>
                    <?= includeTemplate('/_block/show-ip', ['ip' => $answer['answer_ip'], 'user_trust_level' => $uid['user_trust_level']]); ?>
                  </div>
                  <a class="qa-login size-15" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>"><?= $answer['user_login']; ?></a>
                </div>
                <?= $answer['answer_content'] ?>
              </div>
              <div class="flex size-14">
                <?= votes($uid['user_id'], $answer, 'answer'); ?>

                <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_comm_qa')) { ?>
                  <?php if ($data['post']['post_closed'] == 0) { ?>
                    <?php if ($data['post']['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
                      <a data-post_id="<?= $data['post']['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray ml10"><?= lang('reply'); ?></a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
                <?php if ($uid['user_id'] == $answer['answer_user_id'] || $uid['user_trust_level'] == 5) { ?>
                  <a class="editansw gray ml15 mr5" href="/answer/edit/<?= $answer['answer_id']; ?>">
                    <?= lang('edit'); ?>
                  </a>
                <?php } ?>
                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray ml15 mr5">
                    <i title="<?= lang('remove'); ?>" class="bi bi-trash"></i>
                  </a>
                <?php } ?>
                <?php if ($uid['user_id']) { ?>
                  <?php $blue = $answer['favorite_user_id'] ? 'blue' : 'gray'; ?>
                  <span id="fav-comm_<?= $answer['answer_id']; ?>" class="add-favorite gray ml15 mr5 <?= $blue; ?>" data-id="<?= $answer['answer_id']; ?>" data-type="answer">
                    <?php if ($answer['favorite_user_id']) { ?>
                      <i title="<?= lang('remove-favorites'); ?>" class="bi bi-bookmark middle"></i>
                    <?php } else { ?>
                      <i title="<?= lang('add-favorites'); ?>" class="bi bi-bookmark middle"></i>
                    <?php } ?>
                  </span>
                <?php } ?>
                <?php if ($uid['user_id'] != $answer['answer_user_id'] && $uid['user_trust_level'] > Config::get('trust-levels.tl_stop_report')) { ?>
                  <a data-post_id="<?= $data['post']['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray ml15">
                    <i title="<?= lang('report'); ?>" class="bi bi-flag"></i>
                  </a>
                <?php } ?>
              </div>
              <div id="answer_addentry<?= $answer['answer_id']; ?>" class="reply"></div>
            </li>
          </ol>

        <?php } else { ?>
          <ol class="bg-red-300 answer-telo m5 list-none size-14">
            <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
              <span class="answ-deletes">~ <?= lang('answer deleted'); ?></span>
            </li>
          </ol>
        <?php } ?>
      </div>

      <?php $n = 0;
      foreach ($answer['comm'] as  $comment) {
        $n++; ?>
        <?php if ($comment['comment_is_deleted'] == 0) { ?>
          <div class="border-bottom<?php if ($n > 1) { ?> ml30<?php } ?>"></div>
          <ol class="max-w780 size-15 list-none mb0 mt0">
            <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
              <div class="size-14 pt5 pr5 pb5 pl5">
                <?= $comment['comment_content'] ?>
                <span class="gray">
                  — <a class="gray" href="<?= getUrlByName('user', ['login' => $comment['user_login']]); ?>"><?= $comment['user_login']; ?></a>
                  <span class="lowercase gray">
                    &nbsp; <?= lang_date($comment['comment_date']); ?>
                  </span>
                  <?= includeTemplate('/_block/show-ip', ['ip' => $comment['comment_ip'], 'user_trust_level' => $uid['user_trust_level']]); ?>
                </span>

                <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_comm_qa')) { ?>
                  <?php if ($data['post']['post_closed'] == 0) { ?>
                    <?php if ($data['post']['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
                      <a data-post_id="<?= $data['post']['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re gray ml5 mr5">
                        <?= lang('reply'); ?>
                      </a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>

                <?php if ($uid['user_id'] == $comment['comment_user_id'] || $uid['user_trust_level'] == 5) { ?>
                  <a data-post_id="<?= $data['post']['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray ml10 mr5">
                    <i title="<?= lang('edit'); ?>" class="bi bi-pencil-square"></i>
                  </a>
                <?php } ?>

                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray ml10">
                    <i title="<?= lang('remove'); ?>" class="bi bi-trash"></i>
                  </a>
                <?php } ?>
                <?php if ($uid['user_id'] != $comment['comment_user_id'] && $uid['user_trust_level'] > 0) { ?>
                  <a data-post_id="<?= $data['post']['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray ml5">
                    <?= lang('report'); ?>
                  </a>
                <?php } ?>
              </div>
              <div id="comment_addentry<?= $comment['comment_id']; ?>" class="reply"></div>
            </li>
          </ol>
        <?php } else { ?>

        <?php } ?>
      <?php } ?>

    <?php } ?>
  </div>
<?php } else { ?>
  <?php if ($data['post']['post_closed'] != 1) { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'no answers']); ?>
  <?php } ?>
<?php } ?>

<?php if (!empty($otvet)) { ?>
  <?= includeTemplate('/_block/no-content', ['lang' => 'you-question-no']); ?>
<?php } else { ?>
  <?php if ($uid['user_id']) { ?>
    <?php if ($data['post']['post_closed'] == 0) { ?>
      <?= includeTemplate('/_block/editor/answer-create-editor', ['post_id' => $data['post']['post_id'], 'type' => 'answer']); ?>
    <?php } ?>
  <?php } else { ?>
    <?= includeTemplate('/_block/no-content', ['lang' => 'no-auth-login']); ?>
  <?php } ?>
<?php }  ?>