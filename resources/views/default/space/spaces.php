<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?php if ($uid['id'] > 0) { ?>
                    <?php if ($add_space_button === true) { ?>
                        <a title="<?= lang('To create'); ?>" class="right mt5 mb15 size-21" href="/space/add">
                            <i class="icon-plus red"></i>
                        </a>
                    <?php } ?>
                <?php } ?>

                <h1><?= $data['h1']; ?></h1>

                <ul class="nav-tabs">
                    <?php if ($data['sheet'] == 'spaces') { ?>
                        <li class="active">
                            <span><?= lang('All'); ?></span>
                        </li>
                        <?php if ($uid['id'] > 0) { ?>
                            <li>
                                <a href="/space/my">
                                    <span><?= lang('Signed'); ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <li>
                            <a href="/spaces">
                                <span><?= lang('All'); ?></span>
                            </a>
                        </li>
                        <?php if ($uid['id'] > 0) { ?>
                            <li class="active">
                                <span><?= lang('Signed'); ?></span>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>

                <?php if (!empty($spaces)) { ?>
                    <div class="flex all-box-spaces">
                        <?php foreach ($spaces as  $sp) { ?>
                            <?php if ($sp['space_cover_art'] != 'space_cover_no.jpeg') { ?>
                                <div class="fons">
                                    <div class="space-info-box" style="background-image: url(/uploads/spaces/cover/small/<?= $sp['space_cover_art']; ?>)">

                                    <?php } else { ?>
                                        <div class="fon">
                                            <div class="space-info-box" style="background:<?= $sp['space_color']; ?>;">

                                            <?php } ?>
                                            <?php if ($sp['space_id'] != 1) { ?>
                                                <span class="white absolute right-10">+ <?= $sp['users'] ?></span>
                                            <?php } ?>

                                            <a title="<?= $sp['space_name']; ?>" class="space-img-box absolute" href="/s/<?= $sp['space_slug']; ?>">
                                                <?= spase_logo_img($sp['space_img'], 'max', $sp['space_name'], 'ava-54'); ?>
                                            </a>

                                            <span class="space-name">
                                                <a title="<?= $sp['space_name']; ?>" class="space-s white absolute size-21" href="/s/<?= $sp['space_slug']; ?>">
                                                    <span class="space-name"> <?= $sp['space_name']; ?></span>
                                                </a>
                                            </span>

                                            <?php if (!$uid['id']) { ?>
                                                <div class="top-15-px">
                                                    <a href="/login">
                                                        <div class="focus-space yes-space absolute">
                                                            <i class="icon-plus middle"></i>
                                                            <span class="middle"><?= lang('Read'); ?></span>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php } else { ?>
                                                <?php if ($sp['space_id'] != 1) { ?>
                                                    <?php if ($sp['space_user_id'] != $uid['id']) { ?>
                                                        <div class="top-15-px">
                                                            <?php if ($sp['signed_space_id'] >= 1) { ?>
                                                                <div data-id="<?= $sp['space_id']; ?>" data-type="space" class="focus-id focus-space absolute">
                                                                    <i class="icon-ok-outline middle"></i>
                                                                    <span class="middle"><?= lang('Unsubscribe'); ?></span>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div data-id="<?= $sp['space_id']; ?>" data-type="space" class="focus-id focus-space absolute">
                                                                    <i class="icon-plus middle"></i>
                                                                    <span class="middle"><?= lang('Read'); ?></span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if ($sp['space_user_id'] == $uid['id']) { ?>
                                                        <div class="focus-space absolute">
                                                            <i class="icon-ok-outline middle"></i>
                                                            <span class="middle"><?= lang('Created by'); ?></span>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <p class="no-content gray">
                                        <i class="icon-help middle"></i>
                                        <span class="middle"><?= lang('No spaces'); ?>...</span>
                                    </p>
                                <?php } ?>
                                </div>
                    </div>
                    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/spaces'); ?>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?php if ($data['sheet'] == 'spaces') { ?>
                    <?= lang('info_space'); ?>
                <?php } else { ?>
                    <?= lang('my_info_space'); ?>
                <?php } ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>