<?php if (!empty($data['answers'])) { ?>
  <div class="bg-white br-rd5 br-box-gray p15">
    <h2 class="lowercase m0 size-21">
      <?= num_word($post['amount_content'], Translate::get('num-answer'), true); ?>
    </h2>

    <?php foreach ($data['answers'] as  $answer) { ?>
      <div class="block-answer">
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <?php if ($uid['user_id'] == $answer['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>
          <div class="br-top-dotted mb20"></div>
          <ol class="p0 m0 list-none">
            <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
              <div class="answ-telo hidden">
                <div class="br-box-gray w130 br-rd3 right mt10 pt10 ml10 pb10 hidden center">
                  <?= user_avatar_img($answer['user_avatar'], 'max', $answer['user_login'], 'br-rd-50 w64'); ?>
                  <div class="size-14 gray-600">
                    <?= $answer['answer_date']; ?>
                    <?php if (empty($answer['edit'])) { ?>
                      (<?= Translate::get('ed'); ?>.)
                    <?php } ?>
                    <?= import('/_block/show-ip', ['ip' => $answer['answer_ip'], 'user_trust_level' => $uid['user_trust_level']]); ?>
                  </div>
                  <a class="qa-login size-15" href="<?= getUrlByName('user', ['login' => $answer['user_login']]); ?>"><?= $answer['user_login']; ?></a>
                </div>
                <?= $answer['answer_content'] ?>
              </div>
              <div class="flex size-14">
                <?= votes($uid['user_id'], $answer, 'answer', 'mr5'); ?>

                <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_comm_qa')) { ?>
                  <?php if ($post['post_closed'] == 0) { ?>
                    <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
                      <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray ml10"><?= Translate::get('reply'); ?></a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
                <?php if ($uid['user_id'] == $answer['answer_user_id'] || $uid['user_trust_level'] == 5) { ?>
                  <a class="editansw gray ml15 mr5" href="/answer/edit/<?= $answer['answer_id']; ?>">
                    <?= Translate::get('edit'); ?>
                  </a>
                <?php } ?>
                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray ml15 mr5">
                    <i title="<?= Translate::get('remove'); ?>" class="bi bi-trash"></i>
                  </a>
                <?php } ?>
                <?php if ($uid['user_id']) { ?>
                  <?php $blue = $answer['favorite_user_id'] ? 'sky-500' : 'gray'; ?>
                  <span id="fav-comm_<?= $answer['answer_id']; ?>" class="add-favorite gray ml15 mr5 <?= $blue; ?>" data-id="<?= $answer['answer_id']; ?>" data-type="answer">
                    <?php if ($answer['favorite_user_id']) { ?>
                      <i title="<?= Translate::get('remove-favorites'); ?>" class="bi bi-bookmark middle"></i>
                    <?php } else { ?>
                      <i title="<?= Translate::get('add-favorites'); ?>" class="bi bi-bookmark middle"></i>
                    <?php } ?>
                  </span>
                <?php } ?>
                <?php if ($uid['user_id'] != $answer['answer_user_id'] && $uid['user_trust_level'] > Config::get('trust-levels.tl_stop_report')) { ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray ml15">
                    <i title="<?= Translate::get('report'); ?>" class="bi bi-flag"></i>
                  </a>
                <?php } ?>
              </div>
              <div id="answer_addentry<?= $answer['answer_id']; ?>" class="none"></div>
            </li>
          </ol>
        <?php } ?>
      </div>

      <?php $n = 0;
      foreach ($answer['comm'] as  $comment) {
        $n++; ?>
        <?php if ($comment['comment_is_deleted'] == 0) { ?>
          <div class="br-bottom<?php if ($n > 1) { ?> ml30<?php } ?>"></div>
          <ol class="max-w780 size-15 list-none mb0 mt0">
            <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
              <div class="size-14 pt5 pr5 pb5 pl5">
                <?= $comment['comment_content'] ?>
                <span class="gray">
                  — <a class="gray" href="<?= getUrlByName('user', ['login' => $comment['user_login']]); ?>"><?= $comment['user_login']; ?></a>
                  <span class="lowercase gray">
                    &nbsp; <?= lang_date($comment['comment_date']); ?>
                  </span>
                  <?= import('/_block/show-ip', ['ip' => $comment['comment_ip'], 'user_trust_level' => $uid['user_trust_level']]); ?>
                </span>

                <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_comm_qa')) { ?>
                  <?php if ($post['post_closed'] == 0) { ?>
                    <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
                      <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re gray ml5 mr5">
                        <?= Translate::get('reply'); ?>
                      </a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>

                <?php if ($uid['user_id'] == $comment['comment_user_id'] || $uid['user_trust_level'] == 5) { ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray ml10 mr5">
                    <i title="<?= Translate::get('edit'); ?>" class="bi bi-pencil-square"></i>
                  </a>
                <?php } ?>

                <?php if ($uid['user_trust_level'] == 5) { ?>
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray ml10">
                    <i title="<?= Translate::get('remove'); ?>" class="bi bi-trash"></i>
                  </a>
                <?php } ?>
                <?php if ($uid['user_id'] != $comment['comment_user_id'] && $uid['user_trust_level'] > 0) { ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray ml5">
                    <?= Translate::get('report'); ?>
                  </a>
                <?php } ?>
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
    <?= no_content(Translate::get('no answers'), 'bi bi-info-lg'); ?>
  <?php } ?>
<?php } ?>

<?php if (!empty($otvet)) { ?>
  <?= no_content(Translate::get('you-question-no'), 'bi bi-info-lg'); ?>
<?php } else { ?>
  <?php if ($uid['user_id'] > 0) { ?>
    <?php if ($post['post_feature'] == 1 && $post['post_draft'] == 0 && $post['post_closed'] == 0) { ?>

      <form class="mb15" action="<?= getUrlByName('answer.create'); ?>" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
        <?= import('/_block/editor/editor', [
          'height'    => '250px',
          'preview'   => 'tab',
          'lang'      => $uid['user_lang'],
        ]); ?>

        <div class="clear pt5">
          <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
          <input type="hidden" name="answer_id" value="0">
          <?= sumbit(Translate::get('reply')); ?>
        </div>
      </form>

    <?php } ?>
  <?php } ?>
<?php }  ?>