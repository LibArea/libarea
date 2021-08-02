<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <a class="size-13" title="<?= lang('All topics'); ?>" href="/topics"> ← <?= lang('Topics'); ?></a>

                <h1 class="topics"><a href="/topic/<?= $topic['topic_slug']; ?>"><?= $data['h1']; ?></a>
                    <?php if ($uid['trust_level'] == 5) { ?>
                        <a class="right" href="/admin/topics/<?= $topic['topic_id']; ?>/edit">
                            <i class="light-icon-edit middle"></i>
                        </a>
                    <?php } ?>
                </h1>

                <h3><?= lang('Info'); ?></h3>

                <?= $topic['topic_info']; ?>
            </div>
        </div>

        <?php if (!empty($post_related)) { ?>
            <div class="white-box">
                <div class="inner-padding">
                    <div class="related">
                        <h3 class="style size-13"><?= lang('By topic'); ?>:</h3>
                        <?php $num = 0; ?>
                        <?php foreach ($post_related as $related) { ?>
                            <div class="related-box-num">
                                <?php $num++; ?>
                                <span><?= $num; ?></span>
                                <a href="/post/<?= $related['post_id']; ?>/<?= $related['post_slug']; ?>">
                                    <?= $related['post_title']; ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>

    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <center>
                    <a title="<?= $topic['topic_title']; ?>" href="/topic/<?= $topic['topic_slug']; ?>">
                        <div><?= $topic['topic_title']; ?><div>
                                <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'topic-img'); ?>
                    </a>
                </center>
                <hr>
                <div class="sb-created">
                    <i class="light-icon-calendar middle"></i>
                    <span class="middle"><?= $topic['topic_add_date']; ?></span>
                </div>
            </div>
        </div>


        <?php if (!empty($main_topic)) { ?>
            <div class="white-box">
                <div class="inner-padding big">
                    <h3 class="style size-13"><?= lang('Root'); ?></h3>
                    <div class="related-box">
                        <a class="tags size-13" href="/topic/<?= $main_topic['topic_slug']; ?>">
                            <?= $main_topic['topic_title']; ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (!empty($subtopics)) { ?>
            <div class="white-box">
                <div class="inner-padding big">
                    <h3 class="style size-13"><?= lang('Subtopics'); ?></h3>
                    <?php foreach ($subtopics as $sub) { ?>
                        <div class="related-box">
                            <a class="tags size-13" href="/topic/<?= $sub['topic_slug']; ?>">
                                <?= $sub['topic_title']; ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>


        <?php if (!empty($topic_related)) { ?>
            <div class="white-box">
                <div class="inner-padding big">
                    <h3 class="style size-13"><?= lang('Related'); ?></h3>
                    <?php foreach ($topic_related as $related) { ?>
                        <div class="related-box">
                            <a class="tags size-13" href="/topic/<?= $related['topic_slug']; ?>">
                                <?= $related['topic_title']; ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>