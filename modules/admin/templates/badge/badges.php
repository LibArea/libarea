<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <a class="right" title="<?= lang('Add'); ?>" href="/admin/badges/add">
                    <i class="icon-plus middle"></i>
                </a>
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

                <div class="badges">
                    <?php if (!empty($badges)) { ?>

                        <div class="t-table">
                            <div class="t-th">
                                <span class="t-td center">Id</span>
                                <span class="t-td center">Icon</span>
                                <span class="t-td"><?= lang('Title'); ?>&nbsp;/&nbsp;<?= lang('Description'); ?></span>
                                <span class="t-td center"><?= lang('Action'); ?></span>
                            </div>

                            <?php foreach ($badges as $key => $bg) { ?>
                                <div class="t-tr">
                                    <span class="t-td width-30 center">
                                        <?= $bg['badge_id']; ?>
                                    </span>
                                    <span class="t-td width-30 center">
                                        <?= $bg['badge_icon']; ?>
                                    </span>
                                    <span class="t-td">
                                        <b><?= $bg['badge_title']; ?></b>
                                        <br>
                                        <?= $bg['badge_description']; ?>
                                    </span>
                                    <span class="t-td center">
                                        <a title="<?= lang('Edit'); ?>" href="/admin/badges/<?= $bg['badge_id']; ?>/edit">
                                            <i class="icon-pencil size-15"></i>
                                        </a>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <?= no_content('No'); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>