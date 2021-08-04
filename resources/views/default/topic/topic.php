<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <div class="flex">
                    <div>
                        <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'ava-94 mt5'); ?>
                    </div>
                    <div class="ml15 box-100">
                        <h1>
                            <?= $data['h1']; ?>
                            <?php if ($uid['trust_level'] == 5) { ?>
                                <a class="right" href="/admin/topics/<?= $topic['topic_id']; ?>/edit">
                                    <i class="light-icon-edit middle"></i>
                                </a>
                            <?php } ?>
                        </h1>
                        <div class="size-13"><?= $topic['topic_description']; ?></div>
                        <div class="mt15">
                            <?php if (!$uid['id']) { ?>
                                <a href="/login">
                                    <div class="add-focus focus-topic">+ <?= lang('Read'); ?></div>
                                </a>
                            <?php } else { ?>
                                <?php if (is_array($topic_signed)) { ?>
                                    <div data-id="<?= $topic['topic_id']; ?>" data-type="topic" class="focus-id del-focus focus-topic">
                                        <?= lang('Unsubscribe'); ?>
                                    </div>
                                <?php } else { ?>
                                    <div data-id="<?= $topic['topic_id']; ?>" data-type="topic" class="focus-id add-focus focus-topic">
                                        + <?= lang('Read'); ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <a title="<?= lang('Info'); ?>" class="size-13 lowercase right gray" href="/topic/<?= $topic['topic_slug']; ?>/info">
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
            <div class="p15">
                <div class="flex">
                    <div class="box-post center box-number">
                        <div class="style size-13 gray lowercase"><?= lang('Posts-m'); ?></div>
                        <?= $topic['topic_count']; ?>
                    </div>
                    <div class="box-fav center box-number">
                        <div class="style size-13 gray lowercase"><?= lang('Reads'); ?></div>
                        <?= $topic['topic_focus_count']; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($main_topic)) { ?>
            <div class="white-box">
                <div class="p15">
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
                <div class="p15">
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
                <div class="p15">
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