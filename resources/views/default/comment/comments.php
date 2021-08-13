<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1><?= $data['h1']; ?></h1>

                <?php if (!empty($comments)) { ?>

                    <?php foreach ($comments as $comment) { ?>

                        <?php if ($comment['comment_is_deleted'] == 0) { ?>
                            <div class="mb20">
                                <div class="size-13">
                                    <a class="gray" href="/u/<?= $comment['login']; ?>">
                                        <?= user_avatar_img($comment['avatar'], 'small', $comment['login'], 'ava'); ?>
                                        <span class="mr5 ml5">
                                            <?= $comment['login']; ?>
                                        </span>
                                    </a>
                                    <span class="gray lowercase"><?= $comment['date']; ?></span>

                                    <span class="mr5 ml5"> &#183; </span>
                                    <a href="/post/<?= $comment['post_id']; ?>/<?= $comment['post_slug']; ?>#comment_<?= $comment['comment_id']; ?>">
                                        <?= $comment['post_title']; ?>
                                    </a>
                                </div>

                                <div class="comm-telo-body size-15 mt5 mb5">
                                    <?= $comment['comment_content']; ?>
                                </div>

                                <div class="post-full-footer mb20 pb5 hidden gray">
                                    + <?= $comment['comment_votes']; ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="delleted mb20">
                                <div class="voters"></div>
                                ~ <?= lang('Comment deleted'); ?>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>

                <?php } else { ?>
                    <div class="no-content"><?= lang('There are no comments'); ?>...</div>
                <?php } ?>

            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('comments-desc'); ?>
            </div>
        </div>
        <?php if ($uid['id'] == 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
        <?php } ?>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>