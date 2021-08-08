<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <div id="stHeader">
        <a href="/"><i class="light-icon-home middle"></i></a>
        <span class="separator gray middle mr5 ml5">\</span>
        <span class="middle"><?= $post['post_title']; ?></span>
    </div>

    <main>
        <article class="post-full">
            <?php if ($post['post_is_deleted'] == 0 || $uid['trust_level'] == 5) { ?>
                <div class="white-box p15<?php if ($post['post_is_deleted'] == 1) { ?> delleted pl15<?php } ?>">

                    <div class="post-body">
                        <h1 class="title size-21">
                            <?= $post['post_title']; ?>
                            <?php if ($post['post_is_deleted'] == 1) { ?>
                                <i class="light-icon-trash red"></i>
                            <?php } ?>
                            <?php if ($post['post_closed'] == 1) { ?>
                                <i class="light-icon-lock"></i>
                            <?php } ?>
                            <?php if ($post['post_top'] == 1) { ?>
                                <i class="light-icon-arrow-narrow-up red"></i>
                            <?php } ?>
                            <?php if ($post['post_lo'] > 0) { ?>
                                <i class="light-icon-checks red"></i>
                            <?php } ?>
                            <?php if ($post['post_type'] == 1) { ?>
                                <i class="light-icon-language green"></i>
                            <?php } ?>
                            <?php if ($post['post_translation'] == 1) { ?>
                                <span class="translation size-13 italic lowercase"><?= lang('Translation'); ?></span>
                            <?php } ?>
                            <?php if ($post['post_tl'] > 0) { ?>
                                <span class="trust-level italic size-13">tl<?= $post['post_tl']; ?></span>
                            <?php } ?>
                            <?php if ($post['post_merged_id'] > 0) { ?>
                                <i class="light-icon-arrow-forward-up red"></i>
                            <?php } ?>
                        </h1>
                        <div class="size-13 lowercase flex gray-light">
                            <a class="gray" href="/u/<?= $post['login']; ?>">
                                <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
                                <span class="mr5 ml5">
                                    <?= $post['login']; ?>
                                </span>
                            </a>
                            <span class="gray-light">
                                <?= $post['post_date_lang']; ?>
                                <?php if ($post['modified']) { ?>
                                    (<?= lang('ed'); ?>)
                                <?php } ?>
                            </span>
                            <?php if ($uid['id']) { ?>
                                <?php if ($uid['login'] == $post['login']  || $uid['trust_level'] == 5) { ?>
                                    <span class="mr5 ml5">&#183;</span>
                                    <a class="gray-light" href="/post/edit/<?= $post['post_id']; ?>">
                                        <?= lang('Edit'); ?>
                                    </a>
                                <?php } ?>
                                <?php if ($uid['login'] == $post['login']) { ?>
                                    <?php if ($post['post_draft'] == 0) { ?>
                                        <span class="mr5 ml5">&#183;</span>
                                        <?php if ($post['my_post'] == $post['post_id']) { ?>
                                            <span class="mu_post gray-light">+ <?= lang('in-the-profile'); ?></span>
                                        <?php } else { ?>
                                            <a class="user-mypost gray-light" data-opt="1" data-post="<?= $post['post_id']; ?>">
                                                <span class="mu_post"><?= lang('in-the-profile'); ?></span>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <span class="add-favorite gray-light" data-id="<?= $post['post_id']; ?>" data-type="post">
                                    <span class="mr5 ml5">&#183;</span>
                                    <?php if (is_array($post['favorite_post'])) { ?>
                                        <?= lang('remove-favorites'); ?>
                                    <?php } else { ?>
                                        <?= lang('add-favorites'); ?>
                                    <?php } ?>
                                </span>

                                <?php if ($uid['trust_level'] == 5) { ?>
                                    <span class="mr5 ml5"> &#183; </span>
                                    <span id="cm_dell">
                                        <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action gray-light">
                                            <?php if ($post['post_is_deleted'] == 1) { ?>
                                                <?= lang('Recover'); ?>
                                            <?php } else { ?>
                                                <?= lang('Remove'); ?>
                                            <?php } ?>
                                        </a>
                                    </span>
                                    <span class="size-13">
                                        <span class="mr5 ml5"> &#183; </span>
                                        <?= $post['post_hits_count']; ?>
                                    </span>
                                <?php } ?>

                            <?php } ?>
                        </div>
                    </div>
                    <div class="post-body full">
                        <div class="post">

                            <?php if ($post['post_thumb_img']) { ?>
                                <?= post_img($post['post_thumb_img'], $post['post_title'],  'thumb', 'thumbnails'); ?>
                            <?php } ?>

                            <?= $post['post_content']; ?>
                        </div>
                        <?php if ($lo) { ?>
                            <div class="lo-post">
                                <h3 class="recommend">ЛО</h3>
                                <span class="right">
                                    <a rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comment_<?= $lo['comment_id']; ?>">
                                        <i class="light-icon-checks red"></i>
                                    </a>
                                </span>
                                <?= $lo['comment_content']; ?>
                            </div>
                        <?php } ?>
                        <?php if ($post['post_url_domain']) { ?>
                            <div class="italic mt15 mb15">
                                <?= lang('Website'); ?>: <a rel="nofollow noreferrer ugc" href="<?= $post['post_url']; ?>">
                                    <?= $post['post_url_domain']; ?>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if (!empty($post_related)) { ?>
                            <div class="related">
                                <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Related'); ?>:</h3>
                                <?php $num = 0; ?>
                                <?php foreach ($post_related as $related) { ?>
                                    <div class="mb5">
                                        <?php $num++; ?>
                                        <span class="related-count gray-light size-15"><?= $num; ?></span>
                                        <a href="/post/<?= $related['post_id']; ?>/<?= $related['post_slug']; ?>">
                                            <?= $related['post_title']; ?>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if (!empty($topics)) { ?>
                            <div class="related">
                                <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('Topics'); ?>:</h3>
                                <?php foreach ($topics as $topic) { ?>
                                    <a class="tags gray size-13" href="/topic/<?= $topic['topic_slug']; ?>">
                                        <?= $topic['topic_title']; ?>
                                    </a>
                                <?php } ?>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="post-full-footer gray">
                        <?= votes($uid['id'], $post, 'post'); ?>

                       <span class="right gray-light">
                            <i class="light-icon-messages middle"></i>
                            <?= $post['post_answers_count'] + $post['post_comments_count'] ?>
                        </span>

                    </div>
                    <?php if (!$uid['id']) { ?>
                        <a class="right size-13 mb15 add-focus focus-topic" href="/login">
                            + <?= lang('Read'); ?>
                        </a>
                    <?php } else { ?>
                        <?php if (is_array($post_signed)) { ?>
                            <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id size-13 right mb15 del-focus focus-topic">
                                <?= lang('Unsubscribe'); ?>
                            </div>
                        <?php } else { ?>
                            <div data-id="<?= $post['post_id']; ?>" data-type="post" class="focus-id size-13 right mb15 add-focus focus-topic">
                                + <?= lang('Read'); ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    
                    <div>
                        <?php if ($post['post_type'] == 0 && $post['post_draft'] == 0) { ?>
                            <?php if ($uid['id'] > 0) { ?>
                                <?php if ($post['post_closed'] == 0) { ?>
                                    <form id="add_answ" class="new_answer" action="/answer/create" accept-charset="UTF-8" method="post">
                                        <?= csrf_field() ?>
                                        <div id="test-markdown-view-post">
                                            <textarea minlength="6" class="md-post" rows="5" placeholder="<?= lang('write-something'); ?>..." name="answer" id="wmd-input"></textarea>
                                        </div>
                                        <div class="boxline">
                                            <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                                            <input type="hidden" name="answer_id" id="answer_id" value="0">
                                            <input type="submit" class="button" name="answit" value="<?= lang('Reply'); ?>" class="button">
                                        </div>
                                    </form>
                                <?php } ?>
                            <?php } else { ?>
                                <textarea rows="5" class="darkening" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>" name="answer" id="answer"></textarea>
                                <div>
                                    <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="button" disabled="disabled">
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <br>
                        <?php } ?>
                    </div>
                </div>

            <?php } else { ?>
                <div class="telo-detail-post delleted">
                    <?= lang('Post deleted'); ?>...
                </div>
            <?php } ?>

            <?php if ($post['post_draft'] == 0) { ?>
                <?php if ($post['post_type'] == 0) { ?>
                    <?php include TEMPLATE_DIR . '/post/comment-view.php'; ?>
                    <?php if ($post['post_closed'] == 1) { ?>
                        <p class="no-content gray">
                            <i class="light-icon-lock middle"></i>
                            <span class="middle"><?= lang('The post is closed'); ?>...</span>
                        </p>
                    <?php } ?>
                <?php } else { ?>
                    <?php include TEMPLATE_DIR . '/post/questions-view.php'; ?>
                    <?php if ($post['post_closed'] == 1) { ?>
                        <p class="no-content gray">
                            <i class="light-icon-lock middle"></i>
                            <span class="middle"><?= lang('The question is closed'); ?>...</span>
                        </p>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <p class="no-content gray">
                    <i class="light-icon-info-square middle"></i>
                    <span class="middle"><?= lang('This is a draft'); ?>...</span>
                </p>
            <?php } ?>
        </article>
    </main>

    <aside>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <div class="space-info-img">
                    <a title="<?= $post['space_name']; ?>" href="/s/<?= $post['space_slug']; ?>">
                        <?= spase_logo_img($post['space_img'], 'max', $post['space_slug'], 'ava-24'); ?>
                        <span class="mr5 ml5"><?= $post['space_name']; ?></span>
                    </a>
                </div>
                <div class="gray size-13"><?= $post['space_short_text']; ?></div>
            </div>
        </div>
        <?php if ($post['post_content_img']) { ?>
            <div class="white-box">
                <div id="layer-photos" class="layer-photos p15">
                    <?= post_img($post['post_content_img'], $post['post_title'], 'img-post', 'cover', $post['post_content_img']); ?>
                </div>
            </div>
        <?php } ?>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h3 class="recommend size-13"><?= lang('To share'); ?></h3>
                <div class="social center" data-url="<?= Lori\Config::get(Lori\Config::PARAM_URL) . '/post/' . $post['post_id'] . '/' . $post['post_slug']; ?>" data-title="<?= $post['post_title']; ?>">
                    <a class="push gray" data-id="fb"><i class="light-icon-brand-facebook"></i></a>
                    <a class="push gray" data-id="vk">
                        <svg class="vk" xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24">
                            <path class="st0" d="M13.162 18.994c.609 0 .858-.406.851-.915-.031-1.917.714-2.949 2.059-1.604 1.488 1.488 1.796 2.519 3.603 2.519h3.2c.808 0 1.126-.26 1.126-.668 0-.863-1.421-2.386-2.625-3.504-1.686-1.565-1.765-1.602-.313-3.486 1.801-2.339 4.157-5.336 2.073-5.336h-3.981c-.772 0-.828.435-1.103 1.083-.995 2.347-2.886 5.387-3.604 4.922-.751-.485-.407-2.406-.35-5.261.015-.754.011-1.271-1.141-1.539-.629-.145-1.241-.205-1.809-.205-2.273 0-3.841.953-2.95 1.119 1.571.293 1.42 3.692 1.054 5.16-.638 2.556-3.036-2.024-4.035-4.305-.241-.548-.315-.974-1.175-.974h-3.255c-.492 0-.787.16-.787.516 0 .602 2.96 6.72 5.786 9.77 2.756 2.975 5.48 2.708 7.376 2.708z" />
                        </svg>
                    </a>
                    <a class="push gray" data-id="tw"><i class="light-icon-brand-twitter"></i></a>
                </div>
            </div>
        </div>

        <?php if ($recommend) { ?>
            <div class="white-box sticky recommend">
                <div class="pt5 pr15 pb5 pl15">
                    <h3 class="recommend size-13"><?= lang('Recommended'); ?></h3>
                    <?php $n = 0;
                    foreach ($recommend as  $rec_post) {
                        $n++; ?>
                        <div class="l-rec-small">
                            <div class="l-rec">0<?= $n; ?></div>
                            <div class="l-rec-telo">
                                <a class="edit-bl" href="/post/<?= $rec_post['post_id']; ?>/<?= $rec_post['post_slug']; ?>">
                                    <?= $rec_post['post_title']; ?>
                                </a>
                                <?php if ($rec_post['post_answers_count'] != 0) { ?>
                                    <span class="green">+<?= $rec_post['post_answers_count'] ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </aside>
</div>

<script nonce="<?= $_SERVER['nonce']; ?>">
    $(document).ready(function() {
        layer.photos({
            photos: '#layer-photos',
            anim: 4
        });
    });
</script>
<?php include TEMPLATE_DIR . '/footer.php'; ?>