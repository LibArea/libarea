<?php if (!empty($data['answers'])) { ?>
  <div class="bg-white br-rd5 br-box-gray mt15 mb15 p15">
    <h2 class="lowercase m0 mb15 text-2xl">
      <?= num_word($post['amount_content'], Translate::get('num-answer'), true); ?>
    </h2>
    <?php $n = 0;
    foreach ($data['answers'] as  $answer) {
      $n++;
      $post_url = getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]);
    ?>

      <div class="block-answer">
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <?php if ($n != 1) { ?><div class="br-top-dotted mt10 mb10"></div><?php } ?>
          <ol class="p0 m0 list-none">
            <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
              <div class="answ-telo">
                <div class="flex text-sm">
                  <a class="gray-600" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>">
                    <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'w18'); ?>
                    <span class="mr5 ml5">
                      <?= $answer['user_login']; ?>
                    </span>
                  </a>
                  <?php if ($post['post_user_id'] == $answer['answer_user_id']) { ?>
                    <span class="sky-500 mr5 ml0"><i class="bi bi-mic text-sm"></i></span>
                  <?php } ?>
                  <span class="mr5 ml5 gray-400 lowercase">
                    <?= $answer['answer_date']; ?>
                  </span>
                  <?php if (empty($answer['edit'])) { ?>
                    <span class="mr5 ml10 gray-400">
                      (<?= Translate::get('ed'); ?>.)
                    </span>
                  <?php } ?>
                  <a rel="nofollow" class="gray-400 mr5 ml10" href="<?= $post_url; ?>#answer_<?= $answer['answer_id']; ?>"><i class="bi bi-hash"></i></a>
                  <?= import('/_block/show-ip', ['ip' => $answer['answer_ip'], 'user_trust_level' => $uid['user_trust_level']]); ?>
                </div>
                <div class="m0 max-w780">
                  <?= $answer['answer_content'] ?>
                </div>
              </div>
              <div class="flex text-sm">
                <?= votes($uid['user_id'], $answer, 'answer', 'ps', 'mr5'); ?>
 
                <?php if ($post['post_closed'] == 0) { ?>
                  <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
                    <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray-500 mr5 ml10"><?= Translate::get('reply'); ?></a>
                  <?php } ?>
                <?php } ?>

                <?php if ($uid['user_id'] == $answer['answer_user_id'] || $uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
                  <?php if ($answer['answer_after'] == 0 || $uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
                    <a class="editansw gray-500 mr10 ml10" href="/answer/edit/<?= $answer['answer_id']; ?>"> <?= Translate::get('edit'); ?>
                    </a>
                  <?php } ?>
                <?php } ?>

                <?php if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray-500 ml10 mr5">
                    <i title="<?= Translate::get('remove'); ?>" class="bi bi-trash"></i>
                  </a>
                <?php } ?>

                <?= favorite($uid['user_id'], $answer['answer_id'], 'answer', $answer['favorite_tid'], 'ps', 'ml5'); ?>

                <?php if ($uid['user_id'] != $answer['answer_user_id'] && $uid['user_trust_level'] > Config::get('trust-levels.tl_stop_report')) { ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray-500 ml15">
                    <i title="<?= Translate::get('report'); ?>" class="bi bi-flag"></i>
                  </a>
                <?php } ?>
              </div>
              <div id="answer_addentry<?= $answer['answer_id']; ?>" class="none"></div>
            </li>
          </ol>

        <?php } else { ?>

          <?php if ($uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
            <ol class="bg-red-200 text-sm pr5 list-none">
              <li class="comments_subtree" id="comment_<?= $answer['answer_id']; ?>">
                <span class="comm-deletes nick">
                  <?= $answer['answer_content']; ?>
                  <?= Translate::get('answer'); ?> — <?= $answer['user_login']; ?>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action right">
                    <span><?= Translate::get('recover'); ?></span>
                  </a>
                </span>
              </li>
            </ol>
          <?php } else { ?>
            <div class="gray m5 p5 text-sm">
              <span class="answ-deletes">~ <?= Translate::get('comment deleted'); ?></span>
            </div>
          <?php } ?>

        <?php } ?>
      </div>

      <?php foreach ($answer['comm'] as  $comment) { ?>

        <?php if ($comment['comment_is_deleted'] == 1) { ?>
          <?php if (accessСheck($comment, 'comment', $uid, 1, 30) === true) { ?>
            <ol class="bg-red-200 text-sm list-none max-w780 <?php if ($comment['comment_comment_id'] > 0) { ?> ml30<?php } ?>">
              <li class="pr5" id="comment_<?= $comment['comment_id']; ?>">
                <span class="comm-deletes gray">
                  <?= Content::text($comment['comment_content'], 'line'); ?>
                  — <?= $comment['user_login']; ?>
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action right text-sm">
                    <?= Translate::get('recover'); ?>
                  </a>
                </span>
              </li>
            </ol>
          <?php } ?>
        <?php } ?>

        <?php if ($comment['comment_is_deleted'] == 0) { ?>
          <ol class="pl15 pb0 mb0 list-none<?php if ($comment['comment_comment_id'] > 0) { ?> ml30<?php } ?>">
            <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
              <div class="p5">
                <div class="max-w780">
                  <div class="text-sm flex">
                    <a class="gray-600" href="<?= getUrlByName('user', ['login' => $comment['user_login']]); ?>">
                      <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'w18'); ?>
                      <span class="mr5 ml5">
                        <?= $comment['user_login']; ?>
                      </span>
                    </a>
                    <?php if ($post['post_user_id'] == $comment['comment_user_id']) { ?>
                      <span class="sky-500 mr5"><i class="bi bi-mic text-sm"></i></span>
                    <?php } ?>
                    <span class="mr5 ml5 gray-400 lowercase">
                      <?= lang_date($comment['comment_date']); ?>
                    </span>
                    <?php if ($comment['comment_comment_id'] > 0) { ?>
                      <a class="gray-400 mr10 ml10" rel="nofollow" href="<?= $post_url; ?>#comment_<?= $comment['comment_comment_id']; ?>"><i class="bi bi-arrow-up"></i></a>
                    <?php } else { ?>
                      <a class="gray-400 mr10 ml10" rel="nofollow" href="<?= $post_url; ?>#answer_<?= $comment['comment_answer_id']; ?>"><i class="bi bi-arrow-up"></i></a>
                    <?php } ?>
                    <a class="gray-400 mr5 ml5" rel="nofollow" href="<?= $post_url; ?>#comment_<?= $comment['comment_id']; ?>"><i class="bi bi-hash"></i></a>
                    <?= import('/_block/show-ip', ['ip' => $comment['comment_ip'], 'user_trust_level' => $uid['user_trust_level']]); ?>
                  </div>
                  <div class="mt5 mb10">
                    <?= Content::text($comment['comment_content'], 'line'); ?>
                  </div>
                </div>
                <div class="text-sm flex">
                  <?= votes($uid['user_id'], $comment, 'comment', 'ps', 'mr5'); ?>

                  <?php if ($post['post_closed'] == 0) { ?>
                    <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == Base::USER_LEVEL_ADMIN) { ?>
                      <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re gray mr5 ml10">
                        <?= Translate::get('reply'); ?>
                      </a>
                    <?php } ?>
                  <?php } ?>

                  <?php if (accessСheck($comment, 'comment', $uid, 1, 30) === true) { ?>
                    <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray mr10 ml10">
                      <?= Translate::get('edit'); ?>
                    </a>
                    <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray mr5 ml5">
                      <?= Translate::get('remove'); ?>
                    </a>
                  <?php } ?>
                  <?php if ($uid['user_id'] != $comment['comment_user_id'] && $uid['user_trust_level'] > 0) { ?>
                    <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray ml15">
                      <i title="<?= Translate::get('report'); ?>" class="bi bi-flag"></i>
                    </a>
                  <?php } ?>
                </div>
              </div>
              <div id="comment_addentry<?= $comment['comment_id']; ?>" class="none"></div>
            </li>
          </ol>
        <?php } ?>
      <?php } ?>
    <?php } ?>
  </div>
<?php } else { ?>
  <?php if ($post['post_closed'] != 1) { ?>
    <?php if ($uid['user_id'] > 0) { ?>
      <?= no_content(Translate::get('there are no comments'), 'bi bi-info-lg'); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no-auth-login'), 'bi bi-info-lg'); ?>
    <?php } ?>
  <?php } ?>
<?php } ?>