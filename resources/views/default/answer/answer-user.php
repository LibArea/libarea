<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>
            </div>
        </div>

        <?php if (!empty($answers)) { ?>
            <?php foreach ($answers as $answer) { ?>
                <?php if ($answer['answer_is_deleted'] == 0) { ?>
                    <div class="white-box">
                        <div class="post-header size-13">
                            <a class="gray" href="/u/<?= $answer['login']; ?>">
                                <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'ava'); ?>
                                <span class="indent"></span>
                                <?= $answer['login']; ?>
                            </a>
                            <span class="indent gray lowercase">
                                <?= $answer['date']; ?>
                            </span>
                            <a class="indent" href="/post/<?= $answer['post_id']; ?>/<?= $answer['post_slug']; ?>"><?= $answer['post_title']; ?></a>
                        </div>
                        <div class="post-details">
                            <?= $answer['content']; ?>
                        </div>
                        <div class="post-footer gray">
                            <div class="up-id"></div> + <?= $answer['answer_votes']; ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="dell answ-telo_bottom">
                        <div class="voters"></div>
                        ~ <?= lang('Answer deleted'); ?>
                    </div>
                <?php } ?>
            <?php } ?>

        <?php } else { ?>
            <p class="no-content gray">
                <i class="light-icon-info-square middle"></i>
                <span class="middle"><?= lang('No answers'); ?>...</span>
            </p>
        <?php } ?>

    </main>
    <aside>
        <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>