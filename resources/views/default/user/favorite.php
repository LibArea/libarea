<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['login'], lang('Profile'), $data['h1']); ?>

                <div class="favorite max-width">
                    <?php if (!empty($favorite)) { ?>

                        <?php foreach ($favorite as $fav) { ?>

                            <?php if ($fav['favorite_type'] == 1) { ?>
                                <div class="vertical-ind">
                                    <div>
                                        <a href="/post/<?= $fav['post_id']; ?>/<?= $fav['post_slug']; ?>">
                                            <h3 class="title size-21 vertical">
                                                <?= $fav['post_title']; ?>
                                            </h3>
                                        </a>
                                    </div>
                                    <div class="lowercase size-13">
                                        <a class="indent gray" href="/u/<?= $fav['login']; ?>">
                                            <?= user_avatar_img($fav['avatar'], 'small', $fav['login'], 'ava'); ?>
                                            <span class="indent"></span>
                                            <?= $fav['login']; ?>

                                        </a>

                                        <span class="indent gray">
                                            <?= $fav['date']; ?>
                                        </span>

                                        <span class="indent"> </span>
                                        <a class="indent gray" href="/s/<?= $fav['space_slug']; ?>" title="<?= $fav['space_name']; ?>">
                                            <?= $fav['space_name']; ?>
                                        </a>
                                        <?php if ($fav['post_answers_count'] != 0) { ?>
                                            <span class="indent"></span>
                                            <a class="indent gray" href="/post/<?= $fav['post_id']; ?>/<?= $fav['post_slug']; ?>">
                                                <i class="light-icon-messages middle"></i> <?= $fav['post_answers_count'] ?>
                                            </a>
                                        <?php } ?>
                                        <?php if ($uid['id'] > 0) { ?>
                                            <?php if ($uid['id'] == $fav['favorite_user_id']) { ?>
                                                <span class="indent"> </span>
                                                <span class="add-favorite right" data-id="<?= $fav['post_id']; ?>" data-type="post">
                                                    <span class="date"><?= lang('Remove'); ?></span>
                                                </span>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div> <hr>
                            <?php } ?>
                            <?php if ($fav['favorite_type'] == 2) { ?>
                                <div class="post-telo fav-answ">
                                    <div>
                                        <a href="/post/<?= $fav['post']['post_id']; ?>/<?= $fav['post']['post_slug']; ?>#answer_<?= $fav['answer_id']; ?>">
                                            <h3 class="title size-21 vertical">
                                                <?= $fav['post']['post_title']; ?>
                                            </h3>
                                        </a>
                                    </div>    
                                    <div class="space-color space_<?= $fav['post']['space_color'] ?>"></div>
                                    <a class="indent gray size-13" href="/s/<?= $fav['post']['space_slug']; ?>" title="<?= $fav['post']['space_name']; ?>">
                                        <?= $fav['post']['space_name']; ?>
                                    </a>
                                    <?php if ($uid['id'] > 0) { ?>
                                        <?php if ($uid['id'] == $fav['favorite_user_id']) { ?>
                                            <span class="add-favorite right" data-id="<?= $fav['answer_id']; ?>" data-type="answer">
                                                <span class="size-13 date"><?= lang('Remove'); ?></span>
                                            </span>
                                        <?php } ?>
                                    <?php } ?>
                                    <blockquote>
                                        <?= $fav['answer_content']; ?>
                                    </blockquote>
                                </div><hr>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="no-content gray">
                            <i class="light-icon-info-square middle"></i>
                            <span class="middle"><?= lang('There are no favorites'); ?>...</span>
                        </p>
                    <?php } ?>
                    <br>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('Under development'); ?>...
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>