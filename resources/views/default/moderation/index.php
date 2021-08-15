<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1><?= $data['h1']; ?></h1>
                <?php if (!empty($moderations)) { ?>
                    <div class="moderations">

                        <?php foreach ($moderations as  $mod) { ?>
                            <div class="white-box">
                                <div class="size-13 lowercase">
                                    <a href="/u/<?= $mod['user_login']; ?>">
                                        <?= user_avatar_img($mod['user_avatar'], 'small', $mod['user_login'], 'ava'); ?>
                                        <span class="mr5 ml5">
                                            <?= $mod['user_login']; ?>
                                        </span>
                                    </a>
                                    <span class="mr5 ml5">
                                        <?= $mod['mod_created_at']; ?>
                                    </span>
                                </div>
                                <div>
                                    <a href="/post/<?= $mod['post_id']; ?>/<?= $mod['post_slug']; ?>">
                                        <?= $mod['post_title']; ?>
                                    </a>
                                    <?php if ($mod['post_type'] == 1) { ?>
                                        <i class="icon-help green"></i>
                                    <?php } ?>
                                </div>
                                <div class="size-13">
                                    <?= lang('Action'); ?>: <b><?= lang($mod['mod_action']); ?></b>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <?= no_content('No moderation logs'); ?>
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('meta-moderation'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>