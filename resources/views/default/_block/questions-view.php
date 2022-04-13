<?php if (!empty($data['answers'])) { ?>
  <div class="box">
    <h2 class="lowercase m0 text-2xl">
      <?= Html::numWord($post['amount_content'], __('num.answer'), true); ?>
    </h2>

    <?php foreach ($data['answers'] as  $answer) { ?>
      <div class="block-answer mb15">
        <?php if ($answer['answer_is_deleted'] == 0) { ?>
          <?php if ($user['id'] == $answer['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>
          <div class="br-top-dotted mb20"></div>
          <ol class="list-none">
            <li class="content_tree" id="answer_<?= $answer['answer_id']; ?>">
              <div class="hidden">
                <div class="br-gray w110 box right hidden center">
                  <?= Html::image($answer['avatar'], $answer['login'], 'img-lg', 'avatar', 'max'); ?>
                  <div class="text-sm gray">
                    <?= Html::langDate($answer['date']); ?>
                    <?php if (empty($answer['edit'])) { ?>
                      (<?= __('ed'); ?>.)
                    <?php } ?>
                    <?= Tpl::insert('/_block/show-ip', ['ip' => $answer['answer_ip'], 'user' => $user, 'publ' => $answer['answer_published']]); ?>
                  </div>
                  <a class="qa-login" href="<?= getUrlByName('profile', ['login' => $answer['login']]); ?>"><?= $answer['login']; ?></a>
                </div>
                <div class="max-w780">
                  <?= Content::text($answer['answer_content'], 'text'); ?>
                </DIV>
              </div>
              <div class="flex text-sm">
                <?= Html::votes($user['id'], $answer, 'answer', 'ps', 'mr5'); ?>

                <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_comm_qa')) { ?>
                  <?php if ($post['post_closed'] == 0) { ?>
                    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) { ?>
                      <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray ml10 mr10"><?= __('reply'); ?></a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
                
                <?php if (Html::accessСheck($answer, 'answer', $user, 1, 30) === true) { ?>
                  <?php if ($user['id'] == $answer['answer_user_id'] || UserData::checkAdmin()) { ?>
                    <a class="editansw gray ml15 mr10" href="<?= getUrlByName('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]); ?>">
                      <?= __('edit'); ?>
                    </a>
                  <?php } ?>
                <?php } ?>
                
                <?php if (UserData::checkAdmin()) { ?>
                  <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray ml15 mr10">
                    <i title="<?= __('remove'); ?>" class="bi-trash"></i>
                  </a>
                <?php } ?>

                <?= Html::favorite($user['id'], $answer['answer_id'], 'answer', $answer['tid'], 'ps', 'ml10'); ?>

                <?php if ($user['id'] != $answer['answer_user_id'] && $user['trust_level'] > Config::get('trust-levels.tl_stop_report')) { ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray-600 ml15">
                    <i title="<?= __('report'); ?>" class="bi-flag"></i>
                  </a>
                <?php } ?>
              </div>
              <div id="answer_addentry<?= $answer['answer_id']; ?>" class="none"></div>
            </li>
          </ol>
        <?php } ?>
      </div>

      <?php $n = 0;
      foreach ($answer['comments'] as  $comment) {
        $n++; ?>
        <?php if ($comment['comment_is_deleted'] == 0) { ?>
          <div class="br-bottom<?php if ($n > 1) { ?> ml30<?php } ?>"></div>
          <ol class="max-w780 list-none mb0 mt0">
            <li class="content_tree" id="comment_<?= $comment['comment_id']; ?>">
              <div class="text-sm pt5 pr5 pb5 pl5">
                <?= Content::text($comment['comment_content'], 'text'); ?>
                <span class="gray">
                  — <a class="gray" href="<?= getUrlByName('profile', ['login' => $comment['login']]); ?>"><?= $comment['login']; ?></a>
                  <span class="lowercase gray">
                    &nbsp; <?= Html::langDate($comment['date']); ?>
                  </span>
                  <?= Tpl::insert('/_block/show-ip', ['ip' => $comment['comment_ip'], 'user' => $user, 'publ' => $comment['comment_published']]); ?>
                </span>

                <?php if ($user['trust_level'] >= Config::get('trust-levels.tl_add_comm_qa')) { ?>
                  <?php if ($post['post_closed'] == 0) { ?>
                    <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) { ?>
                      <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re gray ml5 mr5">
                        <?= __('reply'); ?>
                      </a>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>

                <?php if (Html::accessСheck($comment, 'comment', $user, 1, 30) === true) { ?>
                  <?php if ($user['id'] == $comment['comment_user_id'] || UserData::checkAdmin()) { ?>
                    <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray ml10 mr5">
                      <i title="<?= __('edit'); ?>" class="bi-pencil-square"></i>
                    </a>
                  <?php } ?>
                <?php } ?>

                <?php if (UserData::checkAdmin()) { ?>
                  <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray ml10">
                    <i title="<?= __('remove'); ?>" class="bi-trash"></i>
                  </a>
                <?php } ?>
                <?php if ($user['id'] != $comment['comment_user_id'] && $user['trust_level'] > 0) { ?>
                  <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray ml5">
                    <?= __('report'); ?>
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
    <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('no.answers'), 'icon' => 'bi-info-lg']); ?>
  <?php } ?>
<?php } ?>

<?php if (!empty($otvet)) { ?>
  <?= Tpl::insert('/_block/no-content', ['type' => 'small', 'text' => __('you.answered'), 'icon' => 'bi-info-lg']); ?>
<?php } else { ?>
  <?php if ($user['id'] > 0) { ?>
    <?php if ($post['post_feature'] == 1 && $post['post_draft'] == 0 && $post['post_closed'] == 0) { ?>

      <form class="mb15" action="<?= getUrlByName('content.create', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
        <?= Tpl::insert('/_block/editor/editor', [
          'height'  => '250px',
          'id'      => $post['post_id'],
          'type'    => 'answer',
        ]); ?>

        <div class="clear pt5">
          <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
          <input type="hidden" name="answer_id" value="0">
          <?= Html::sumbit(__('reply')); ?>
        </div>
      </form>

    <?php } ?>
  <?php } ?>
<?php }  ?>