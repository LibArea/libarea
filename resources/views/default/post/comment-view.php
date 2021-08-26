<?php if (!empty($answers)) { ?>
  <div class="white-box p15">
    <h2 class="lowercase m0 size-21">
      <?= $post['post_answers_count'] + $post['post_comments_count'] ?> <?= $post['num_comments'] ?>
    </h2>
    <div class="border-bottom mt5 mb10"></div>
    <?php $n = 0;
    foreach ($answers as  $answer) {
      $n++; ?>

      <div class="block-answer">
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <?php if ($n != 1) { ?><div class="line mt10 mb10"></div><?php } ?>
          <ol class="p0 m0 list-none">
            <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
              <div class="container">
                <div class="answ-telo">
                  <div class="flex size-13">
                    <a class="gray-light" href="/u/<?= $answer['user_login']; ?>">
                      <?= user_avatar_img($answer['user_avatar'], 'small', $answer['user_login'], 'ava'); ?>
                      <span class="mr5 ml5">
                        <?= $answer['user_login']; ?>
                      </span>
                    </a>

                    <span class="mr5 ml5 gray-light lowercase">
                      <?= $answer['answer_date']; ?>
                    </span>
                    <?php if (empty($answer['edit'])) { ?>
                      <span class="mr5 ml5 gray-light">
                        (<?= lang('ed'); ?>.)
                      </span>
                    <?php } ?>
                    <?php if ($post['post_user_id'] == $answer['answer_user_id']) { ?>
                      <span class="mr5 ml5">
                        <span class="red">&#x21af;</span>
                      </span>
                    <?php } ?>
                    <span class="mr5 ml5">
                      <a rel="nofollow" class="gray-light" href="<?= post_url($post); ?>#answer_<?= $answer['answer_id']; ?>">#</a>
                    </span>
                    <?php if ($uid['user_trust_level'] == 5) { ?>
                      <span class="mr5 ml5">
                        <a class="gray-light" href="/admin/logip/<?= $answer['answer_ip']; ?>">
                          <?= $answer['answer_ip']; ?>
                        </a>
                      </span>
                    <?php } ?>
                  </div>
                  <div class="mt5 mr0 mb10 ml0 size-15">
                    <?= $answer['answer_content'] ?>
                  </div>
                </div>
                <div class="flex size-13">
                  <?= votes($uid['user_id'], $answer, 'answer'); ?>

                  <?php if ($post['post_closed'] == 0) { ?>
                    <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
                      <span class="mr5 ml5">
                        <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray"><?= lang('Reply'); ?></a>
                      </span>
                    <?php } ?>
                  <?php } ?>

                  <?php if ($uid['user_id'] == $answer['answer_user_id'] || $uid['user_trust_level'] == 5) { ?>
                    <?php if ($answer['answer_after'] == 0 || $uid['user_trust_level'] == 5) { ?>
                      <span id="answer_edit" class="mr10 ml10">
                        <a class="editansw gray" href="/answer/edit/<?= $answer['answer_id']; ?>"> <?= lang('Edit'); ?>
                        </a>
                      </span>
                    <?php } ?>
                  <?php } ?>

                  <?php if ($uid['user_id']) { ?>
                    <a class="add-favorite mr5 ml5 gray" data-id="<?= $answer['answer_id']; ?>" data-type="answer">
                      <?php if ($answer['favorite_user_id']) { ?>
                        <?= lang('remove-favorites'); ?>
                      <?php } else { ?>
                        <?= lang('add-favorites'); ?>
                      <?php } ?>
                    </a>
                  <?php } ?>

                  <?php if ($uid['user_trust_level'] == 5) { ?>
                    <span id="answer_dell" class="ml10">
                      <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray">
                        <?= lang('Remove'); ?>
                      </a>
                    </span>
                  <?php } ?>
                  <?php if ($uid['user_id'] != $answer['answer_user_id'] && $uid['user_trust_level'] > 0) { ?>
                    <span id="answer_dell" class="ml15">
                      <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray">
                        <?= lang('Report'); ?>
                      </a>
                    </span>
                  <?php } ?>
                </div>
              </div>
              <div id="answer_addentry<?= $answer['answer_id']; ?>" class="reply"></div>

            </li>
          </ol>

        <?php } else { ?>

          <?php if ($uid['user_trust_level'] == 5) { ?>
            <ol class="delleted size-13 pr5 list-none">
              <li class="comments_subtree" id="comment_<?= $answer['answer_id']; ?>">
                <span class="comm-deletes nick">
                  <?= $answer['answer_content']; ?>
                  <?= lang('Answer'); ?> — <?= $answer['user_login']; ?>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action right">
                    <span><?= lang('Recover'); ?></span>
                  </a>
                </span>
              </li>
            </ol>
          <?php } else { ?>
            <div class="gray m5 p5 size-13">
              <span class="answ-deletes">~ <?= lang('Comment deleted'); ?></span>
            </div>
          <?php } ?>

        <?php } ?>
      </div>

      <?php foreach ($answer['comm'] as  $comment) { ?>
        <?php if ($comment['comment_is_deleted'] == 0) { ?>

          <ol class="pl15 list-none<?php if ($comment['comment_comment_id'] > 0) { ?> ml30<?php } ?>">
            <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
              <div class="p5">
                <div class="max-width size-15">
                  <div class="size-13 flex">
                    <a class="gray-light" href="/u/<?= $comment['user_login']; ?>">
                      <?= user_avatar_img($comment['user_avatar'], 'small', $comment['user_login'], 'ava'); ?>
                      <span class="mr5 ml5">
                        <?= $comment['user_login']; ?>
                      </span>
                    </a>

                    <span class="mr5 ml5 gray-light lowercase">
                      <?= lang_date($comment['comment_date']); ?>
                    </span>
                    <?php if ($post['post_user_id'] == $comment['comment_user_id']) { ?>
                      <span class="mr10 ml10">
                        <span class="red">&#x21af;</span>
                      </span>
                    <?php } ?>
                    <?php if ($comment['comment_comment_id'] > 0) { ?>
                      <span class="mr10 ml10">
                        <a class="gray-light" rel="nofollow" href="<?= post_url($post); ?>#comment_<?= $comment['comment_comment_id']; ?>">&uarr;</a>
                      </span>
                    <?php } else { ?>
                      <span class="mr10 ml10">
                        <a class="gray-light" rel="nofollow" href="<?= post_url($post); ?>#answer_<?= $comment['comment_answer_id']; ?>">&uarr;</a>
                      </span>
                    <?php } ?>
                    <span class="mr10 ml10">
                      <a class="gray-light" rel="nofollow" href="<?= post_url($post); ?>#comment_<?= $comment['comment_id']; ?>">#</a>
                    </span>
                    <?php if ($uid['user_trust_level'] == 5) { ?>
                      <span class="ml10">
                        <a class="gray-light" href="/admin/logip/<?= $comment['comment_ip']; ?>">
                          <?= $comment['comment_ip']; ?>
                        </a>
                      </span>
                    <?php } ?>
                  </div>
                  <div class="comm-telo-body size-15 mt5 mb5">
                    <?= lori\Content::text($comment['comment_content'], 'line'); ?>
                  </div>
                </div>
                <div class="size-13 flex">
                  <?= votes($uid['user_id'], $comment, 'comment'); ?>

                  <?php if ($post['post_closed'] == 0) { ?>
                    <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
                      <span class="mr5 ml5">
                        <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re gray">
                          <?= lang('Reply'); ?>
                        </a>
                      </span>
                    <?php } ?>
                  <?php } ?>

                  <?php if (accessСheck($comment, 'comment', $uid, 1, 30) === true) { ?>
                    <span id="comment_edit" class="mr10 ml10">
                      <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray">
                        <?= lang('Edit'); ?>
                      </a>
                    </span>
                    <span id="comment_dell" class="mr5 ml5">
                      <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray">
                        <?= lang('Remove'); ?>
                      </a>
                    </span>
                  <?php } ?>
                  <?php if ($uid['user_id'] != $comment['comment_user_id'] && $uid['user_trust_level'] > 0) { ?>
                    <span id="answer_dell" class="ml15">
                      <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray">
                        <?= lang('Report'); ?>
                      </a>
                    </span>
                  <?php } ?>
                </div>
              </div>
              <div id="comment_addentry<?= $comment['comment_id']; ?>" class="reply"></div>
            </li>
          </ol>

        <?php } else { ?>
          <?php if (accessСheck($comment, 'comment', $uid, 1, 30) === true) { ?>
            <ol class="delleted size-13 list-none max-width size-15<?php if ($comment['comment_comment_id'] > 0) { ?> ml30<?php } ?>">
              <li class="pr5" id="comment_<?= $comment['comment_id']; ?>">
                <span class="comm-deletes gray">
                  <?= lori\Content::text($comment['comment_content'], 'line'); ?>
                  — <?= $comment['user_login']; ?>
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action right size-13">
                    <?= lang('Recover'); ?>
                  </a>
                </span>
              </li>
            </ol>
          <?php } ?>
        <?php } ?>
      <?php } ?>

    <?php } ?>
  </div>
<?php } else { ?>
  <?php if ($post['post_closed'] != 1) { ?>
    <?= no_content('There are no comments'); ?>
  <?php } ?>
<?php } ?>