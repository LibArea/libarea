<?php if (!empty($answers)) { ?>
    <div class="white-box p15">
        <h2 class="lowercase m0 size-21">
            <?= $post['post_answers_count'] ?> <?= $post['num_answers'] ?>
        </h2>

        <?php foreach ($answers as  $answer) { ?>
            <div class="block-answer">
                <?php if ($answer['answer_is_deleted'] == 0) { ?>

                    <?php if ($uid['user_id'] == $answer['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>

                    <div class="line mb20"></div>
                    <ol class="p0 m0">
                        <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
                            <div class="answ-telo hidden">
                                <div class="qa-footer right mt10 pt10 pb10 hidden center">
                                    <div class="qa-ava">
                                        <?= user_avatar_img($answer['user_avatar'], 'max', $answer['user_login'], 'avatar'); ?>
                                    </div>
                                    <div class="qa-ava-info">
                                        <div class="size-13 gray-light">
                                            <?= $answer['answer_date']; ?>
                                            <?php if (empty($answer['edit'])) { ?>
                                                (<?= lang('ed'); ?>.)
                                            <?php } ?>
                                            <?php if ($uid['user_trust_level'] == 5) { ?>
                                                <?= $answer['answer_ip']; ?>
                                            <?php } ?>
                                        </div>
                                        <a class="qa-login" href="/u/<?= $answer['user_login']; ?>"><?= $answer['user_login']; ?></a>
                                    </div>
                                </div>

                                <?= $answer['answer_content'] ?>
                            </div>
                            <div class="answer-footer flex size-13">
                                <?= votes($uid['user_id'], $answer, 'answer'); ?>
                            
                                <?php if ($uid['user_trust_level'] >= Lori\Config::get(Lori\Config::PARAM_TL_ADD_COMM_QA)) { ?>
                                    <?php if ($post['post_closed'] == 0) { ?>
                                        <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
                                            <span class="ml15">
                                                <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray"><?= lang('Reply'); ?></a>
                                            </span>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>

                                <?php if ($uid['user_id'] == $answer['answer_user_id'] || $uid['user_trust_level'] == 5) { ?>
                                    <span id="answer_edit" class="ml15">
                                        <a class="editansw gray" href="/answer/edit/<?= $answer['answer_id']; ?>">
                                            <?= lang('Edit'); ?>
                                        </a>
                                    </span>
                                <?php } ?>

                                <?php if ($uid['user_id']) { ?>
                                    <span class="add-favorite gray ml15" data-id="<?= $answer['answer_id']; ?>" data-type="answer">
                                        <?php if ($answer['favorite_user_id']) { ?>
                                            <?= lang('remove-favorites'); ?>
                                        <?php } else { ?>
                                            <?= lang('add-favorites'); ?>
                                        <?php } ?>
                                    </span>
                                <?php } ?>

                                <?php if ($uid['user_trust_level'] == 5) { ?>
                                    <span class="mr5 ml5"></span>
                                    <span id="answer_dell" class="ml15">
                                        <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray">
                                            <?= lang('Remove'); ?>
                                        </a>
                                    </span>
                                <?php } ?>
                                <?php if ($uid['user_trust_level'] > 0) { ?>
                                    <span id="answer_dell" class="ml15">
                                        <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray">
                                            <?= lang('Report'); ?>
                                        </a>
                                    </span>
                                <?php } ?>
                            </div>
                            <div id="answer_addentry<?= $answer['answer_id']; ?>" class="reply"></div>
                        </li>
                    </ol>

                <?php } else { ?>
                    <ol class="delleted answer-telo m5 size-13">
                        <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
                            <span class="answ-deletes">~ <?= lang('Answer deleted'); ?></span>
                        </li>
                    </ol>
                <?php } ?>
            </div>

            <?php $n = 0;
            foreach ($answer['comm'] as  $comment) { 
                $n++; ?>
                <?php if ($comment['comment_is_deleted'] == 0) { ?>
                    <div class="line-qa<?php if ($n > 1) { ?> ml30<?php } ?>"></div>
                    <ol class="comm-telo mb0 mt0">
                        <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
                            
                            <div class="comm-telo">
                                <div class="size-13 pt5 pr5 pb5 pl5">
                                    <?= $comment['comment_content'] ?>
                                    <span class="gray">
                                        — <a class="gray" href="/u/<?= $comment['user_login']; ?>"><?= $comment['user_login']; ?></a>
                                        <span class="lowercase gray">
                                            &nbsp; <?= lang_date($comment['comment_date']); ?>
                                        </span>
                                        <?php if ($uid['user_trust_level'] == 5) { ?>
                                            &nbsp; <?= $comment['comment_ip']; ?>
                                        <?php } ?>
                                    </span>

                                    <?php if ($uid['user_trust_level'] >= Lori\Config::get(Lori\Config::PARAM_TL_ADD_COMM_QA)) { ?>
                                        <?php if ($post['post_closed'] == 0) { ?>
                                            <?php if ($post['post_is_deleted'] == 0 || $uid['user_trust_level'] == 5) { ?>
                                                <span class="ml5">
                                                    <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re gray">
                                                        <?= lang('Reply'); ?>
                                                    </a>
                                                </span>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if ($uid['user_id'] == $comment['comment_user_id'] || $uid['user_trust_level'] == 5) { ?>
                                        <span id="comment_edit" class="ml5">
                                            <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray">
                                                <?= lang('Edit'); ?>
                                            </a>
                                        </span>
                                    <?php } ?>

                                    <?php if ($uid['user_trust_level'] == 5) { ?>
                                        <span id="comment_dell" class="ml5">
                                            <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray">
                                                <?= lang('Remove'); ?>
                                            </a>
                                        </span>
                                    <?php } ?>
                                    <?php if ($uid['user_trust_level'] > 0) { ?>
                                        <span id="answer_dell" class="ml5">
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

                <?php } ?>
            <?php } ?>

        <?php } ?>
    </div>
<?php } else { ?>
    <?php if ($post['post_closed'] != 1) { ?>
        <?= no_content('No answers'); ?>
    <?php } ?>
<?php } ?>

<?php if (!empty($otvet)) { ?>
    <?= no_content('you-question-no'); ?>
<?php } else { ?>
    <?php if ($uid['user_id']) { ?>
        <?php if ($post['post_closed'] == 0) { ?>
            <form id="add_answ" action="/answer/create" accept-charset="UTF-8" method="post">
                <?= csrf_field() ?>
                <div id="test-markdown-view-post">
                    <textarea minlength="6" class="wmd-input h-150 w-95" rows="5" name="answer" id="wmd-input"></textarea>
                </div>
                <div class="clear">
                    <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                    <input type="hidden" name="answer_id" id="answer_id" value="0">
                    <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="button">
                </div>
            </form>

        <?php } ?>
    <?php } else { ?>
        <?= no_content('no-auth-login'); ?>
    <?php } ?>
<?php }  ?>