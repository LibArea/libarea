<?php if (!empty($answers)) { ?>
    <div class="white-box p15">
        <h2 class="lowercase m0 size-21">
            <?= $post['post_answers_count'] + $post['post_comments_count'] ?> <?= $post['num_comments'] ?>
        </h2>

        <?php foreach ($answers as  $answer) { ?>
            <div class="block-answer">
                <?php if ($answer['answer_is_deleted'] == 0) { ?>
                    <div class="line"></div>
                    <ol class="p0 m0">
                        <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
                            <div class="container">
                                <div class="answ-telo">
                                    <div class="flex size-13">
                                        <a class="gray-light" href="/u/<?= $answer['login']; ?>">
                                            <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'ava'); ?>
                                            <span class="mr5 ml5">
                                                <?= $answer['login']; ?>
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
                                                <span class="authorpost">&#x21af;</span>
                                            </span>
                                        <?php } ?>
                                        <span class="mr5 ml5">
                                            <a rel="nofollow" class="gray-light" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#answer_<?= $answer['answer_id']; ?>">#</a>
                                        </span>
                                        <?php if ($uid['trust_level'] == 5) { ?>
                                            <span class="mr5 ml5 gray-light">
                                                <?= $answer['answer_ip']; ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                    <div class="answ-telo-body">
                                        <?= $answer['answer_content'] ?>
                                    </div>
                                </div>
                                <div class="comm-footer flex size-13">
                                    <?= votes($uid['id'], $answer, 'answer'); ?>

                                    <?php if ($post['post_closed'] == 0) { ?>
                                        <?php if ($post['post_is_deleted'] == 0 || $uid['trust_level'] == 5) { ?>
                                            <span class="mr5 ml5">
                                                <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray"><?= lang('Reply'); ?></a>
                                            </span>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if ($uid['id'] == $answer['answer_user_id'] || $uid['trust_level'] == 5) { ?>
                                        <?php if ($answer['answer_after'] == 0 || $uid['trust_level'] == 5) { ?>
                                            <span id="answer_edit" class="mr10 ml10">
                                                <a class="editansw gray" href="/answer/edit/<?= $answer['answer_id']; ?>"> <?= lang('Edit'); ?>
                                                </a>
                                            </span>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if ($uid['id']) { ?>
                                        <a class="add-favorite mr5 ml5 gray" data-id="<?= $answer['answer_id']; ?>" data-type="answer">
                                            <?php if ($answer['favorite_user_id']) { ?>
                                                <?= lang('remove-favorites'); ?>
                                            <?php } else { ?>
                                                <?= lang('add-favorites'); ?>
                                            <?php } ?>
                                        </a>
                                    <?php } ?>

                                    <?php if ($uid['trust_level'] == 5) { ?>
                                        <span id="answer_dell" class="ml10">
                                            <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray">
                                                <?= lang('Remove'); ?>
                                            </a>
                                        </span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div id="answer_addentry<?= $answer['answer_id']; ?>" class="reply"></div>

                        </li>
                    </ol>

                <?php } else { ?>

                    <?php if ($uid['trust_level'] == 5) { ?>
                        <ol class="delleted size-13 comm-telo">
                            <li class="comments_subtree" id="comment_<?= $answer['answer_id']; ?>">
                                <span class="comm-deletes nick">
                                    <?= $answer['answer_content']; ?>
                                    <?= lang('Answer'); ?> — <?= $answer['login']; ?>
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

                    <ol class="pl15<?php if ($comment['comment_comment_id'] > 0) { ?> ml30<?php } ?>">
                        <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
                            <div class="p5">
                                <div class="comm-telo">
                                    <div class="size-13 flex">
                                        <a class="gray-light" href="/u/<?= $comment['login']; ?>">
                                            <?= user_avatar_img($comment['avatar'], 'small', $comment['login'], 'ava'); ?>
                                            <span class="mr5 ml5">
                                                <?= $comment['login']; ?>
                                            </span>
                                        </a>

                                        <span class="mr5 ml5 gray-light lowercase">
                                            <?= lang_date($comment['comment_date']); ?>
                                        </span>
                                        <?php if ($post['post_user_id'] == $comment['comment_user_id']) { ?>
                                            <span class="mr5 ml5">
                                                <span class="authorpost">&#x21af;</span>
                                            </span>
                                        <?php } ?>
                                        <?php if ($comment['comment_comment_id'] > 0) { ?>
                                            <span class="mr5 ml5">
                                                <a class="gray-light" rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comment_<?= $comment['comment_comment_id']; ?>">&uarr;</a>
                                            </span>
                                        <?php } else { ?>
                                            <span class="mr5 ml5">
                                                <a class="gray-light" rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#answer_<?= $comment['comment_answer_id']; ?>">&uarr;</a>
                                            </span>
                                        <?php } ?>
                                        <span class="mr5 ml5">
                                            <a class="gray-light" rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comment_<?= $comment['comment_id']; ?>">#</a>
                                        </span>
                                        <?php if ($uid['trust_level'] == 5) { ?>
                                            <span class="mr5 ml5 gray-light">
                                                <?= $comment['comment_ip']; ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                    <div class="comm-telo-body size-15 mt5 mb5">
                                        <?= lori\Content::text($comment['comment_content'], 'line'); ?>
                                    </div>
                                </div>
                                <div class="comm-footer size-13 flex">
                                    <?= votes($uid['id'], $comment, 'comment'); ?>

                                    <?php if ($post['post_closed'] == 0) { ?>
                                        <?php if ($post['post_is_deleted'] == 0 || $uid['trust_level'] == 5) { ?>
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
                                </div>
                            </div>
                            <div id="comment_addentry<?= $comment['comment_id']; ?>" class="reply"></div>
                        </li>
                    </ol>

                <?php } else { ?>
                    <?php if (accessСheck($comment, 'comment', $uid, 1, 30) === true) { ?>
                        <ol class="delleted size-13 comm-telo<?php if ($comment['comment_comment_id'] > 0) { ?> ml30<?php } ?>">
                            <li class="comments_subtree" id="comment_<?= $comment['comment_id']; ?>">
                                <span class="comm-deletes gray">
                                    <?= lori\Content::text($comment['comment_content'], 'line'); ?>
                                    — <?= $comment['login']; ?>
                                    <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action right">
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
        <div class="no-content gray">
            <i class="icon-info green middle"></i>
            <span class="middle"><?= lang('There are no comments'); ?>...</span>
        </div>
    <?php } ?>
<?php } ?>