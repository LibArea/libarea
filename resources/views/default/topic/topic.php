<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">

                <div class="flex">
                    <div>
                        <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'ava-94'); ?>
                    </div>
                    <div class="indent-big box-100">
                        <h1>
                            <?= $data['h1']; ?>
                            <?php if ($uid['trust_level'] == 5) { ?>
                                <a class="right" href="/admin/topics/<?= $topic['topic_id']; ?>/edit">
                                    <i class="light-icon-edit middle"></i>
                                </a>
                            <?php } ?>
                        </h1>
                        <div class="small"><?= $topic['topic_description']; ?></div>
                        <div class="topics-footer">
                            <?php if (!$uid['id']) { ?>
                                <a href="/login">
                                    <div class="add-focus focus-topic">+ <?= lang('Read'); ?></div>
                                </a>
                            <?php } else { ?>
                                <?php if ($topic_signed == 1) { ?>
                                    <div data-id="<?= $topic['topic_id']; ?>" class="del-focus focus-topic">
                                        <?= lang('Unsubscribe'); ?>
                                    </div>
                                <?php } else { ?>
                                    <div data-id="<?= $topic['topic_id']; ?>" class="add-focus focus-topic">
                                        + <?= lang('Read'); ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <a title="<?= lang('Info'); ?>" class="small lowercase right gray" href="/topic/<?= $topic['topic_slug']; ?>/info">
                                <i class="light-icon-info-square"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <?php include TEMPLATE_DIR . '/_block/post.php'; ?>

        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/topic/' . $topic['topic_slug']); ?>

    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <div class="flex">
                    <div class="box-post center box-number">
                        <div class="style small gray lowercase"><?= lang('Posts-m'); ?></div>
                        <?= $topic['topic_count']; ?>
                    </div>
                    <div class="box-fav center box-number">
                        <div class="style small gray lowercase"><?= lang('Reads'); ?></div>
                        <?= $topic['topic_focus_count']; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($main_topic)) { ?>
            <div class="white-box">
                <div class="inner-padding big">
                    <h3 class="style small"><?= lang('Root'); ?></h3>
                    <div class="related-box">
                        <a class="tags small" href="/topic/<?= $main_topic['topic_slug']; ?>">
                            <?= $main_topic['topic_title']; ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>

        <?php if (!empty($subtopics)) { ?>
            <div class="white-box">
                <div class="inner-padding big">
                    <h3 class="style small"><?= lang('Subtopics'); ?></h3>
                    <?php foreach ($subtopics as $sub) { ?>
                        <div class="related-box">
                            <a class="tags small" href="/topic/<?= $sub['topic_slug']; ?>">
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
                    <h3 class="style small"><?= lang('Related'); ?></h3>
                    <?php foreach ($topic_related as $related) { ?>
                        <div class="related-box">
                            <a class="tags small" href="/topic/<?= $related['topic_slug']; ?>">
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