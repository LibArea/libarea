<?php if (!empty($answers)) { ?>
    <div class="white-box p15">
        <h2 class="lowercase m0 size-21">
            <?= $post['post_answers_count'] ?> <?= $post['num_answers'] ?>
        </h2>

        <?php foreach ($answers as  $answer) { ?>
            <div class="block-answer">
                <?php if ($answer['answer_is_deleted'] == 0) { ?>

                    <?php if ($uid['id'] == $answer['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>

                    <div class="line"></div>
                    <ol class="p0 m0">
                        <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
                            <div class="answ-telo hidden">
                                <div class="qa-footer mt10 pt10 pb10 hidden center">
                                    <div class="qa-ava">
                                        <?= user_avatar_img($answer['avatar'], 'max', $answer['login'], 'avatar'); ?>
                                    </div>
                                    <div class="qa-ava-info">
                                        <div class="size-13 gray-light">
                                            <?= $answer['answer_date']; ?>
                                            <?php if (empty($answer['edit'])) { ?>
                                                (<?= lang('ed'); ?>.)
                                            <?php } ?>
                                            <?php if ($uid['trust_level'] == 5) { ?>
                                                <?= $answer['answer_ip']; ?>
                                            <?php } ?>
                                        </div>
                                        <a class="qa-login" href="/u/<?= $answer['login']; ?>"><?= $answer['login']; ?></a>
                                    </div>
                                </div>

                                <?= $answer['answer_content'] ?>
                            </div>
                            <div class="answer-footer flex size-13">
                                <?= votes($uid['id'], $answer, 'answer'); ?>
                            
                                <?php if ($uid['trust_level'] >= Lori\Config::get(Lori\Config::PARAM_TL_ADD_COMM_QA)) { ?>
                                    <?php if ($post['post_closed'] == 0) { ?>
                                        <?php if ($post['post_is_deleted'] == 0 || $uid['trust_level'] == 5) { ?>
                                            <span class="mr5 ml5">
                                                <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray"><?= lang('Reply'); ?></a>
                                            </span>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>

                                <?php if ($uid['id'] == $answer['answer_user_id'] || $uid['trust_level'] == 5) { ?>
                                    <span id="answer_edit" class="mr5 ml5">
                                        <a class="editansw gray" href="/answer/edit/<?= $answer['answer_id']; ?>">
                                            <?= lang('Edit'); ?>
                                        </a>
                                    </span>
                                <?php } ?>

                                <?php if ($uid['id']) { ?>
                                    <span class="add-favorite gray mr5 ml5" data-id="<?= $answer['answer_id']; ?>" data-type="answer">
                                        <?php if ($answer['favorite_user_id']) { ?>
                                            <?= lang('remove-favorites'); ?>
                                        <?php } else { ?>
                                            <?= lang('add-favorites'); ?>
                                        <?php } ?>
                                    </span>
                                <?php } ?>

                                <?php if ($uid['trust_level'] == 5) { ?>
                                    <span class="mr5 ml5"></span>
                                    <span id="answer_dell" class="mr5 ml5">
                                        <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray">
                                            <?= lang('Remove'); ?>
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

            <?php foreach ($answer['comm'] as  $comment) { ?>
                <?php if ($comment['comment_is_deleted'] == 0) { ?>

                    <ol class="comm-telo mb0 mt0 qa-comm2">
                        <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
                            <div class="line-qa ml15"></div>
                            <div class="comm-telo">
                                <div class="size-13 pt5 pr5 pb5 pl15">
                                    <?= $comment['comment_content'] ?>
                                    <span class="gray">
                                        — <a class="gray" href="/u/<?= $comment['login']; ?>"><?= $comment['login']; ?></a>
                                        <span class="lowercase gray">
                                            &nbsp; <?= lang_date($comment['comment_date']); ?>
                                        </span>
                                        <?php if ($uid['trust_level'] == 5) { ?>
                                            &nbsp; <?= $comment['comment_ip']; ?>
                                        <?php } ?>
                                    </span>

                                    <?php if ($uid['trust_level'] >= Lori\Config::get(Lori\Config::PARAM_TL_ADD_COMM_QA)) { ?>
                                        <?php if ($post['post_closed'] == 0) { ?>
                                            <?php if ($post['post_is_deleted'] == 0 || $uid['trust_level'] == 5) { ?>
                                                <span class="ml5">
                                                    <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re gray">
                                                        <?= lang('Reply'); ?>
                                                    </a>
                                                </span>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if ($uid['id'] == $comment['comment_user_id'] || $uid['trust_level'] == 5) { ?>
                                        <span id="comment_edit" class="ml5">
                                            <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray">
                                                <?= lang('Edit'); ?>
                                            </a>
                                        </span>
                                    <?php } ?>

                                    <?php if ($uid['trust_level'] == 5) { ?>
                                        <span id="comment_dell" class="ml5">
                                            <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray">
                                                <?= lang('Remove'); ?>
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
        <div class="no-content gray">
            <i class="icon-info green middle"></i>
            <span class="middle"><?= lang('No answers'); ?>... </span>
        </div>
    <?php } ?>
<?php } ?>

<?php if (!empty($otvet)) { ?>

    <div class="no-content gray">
        <i class="icon-info green middle"></i>
        <span class="middle"><?= lang('you-question-no'); ?>...</span>
    </div>

<?php } else { ?>
    <?php if ($uid['id']) { ?>
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
        <div class="no-content gray">
            <?= lang('no-auth-login'); ?>...
        </div>
    <?php } ?>
<?php }  ?>