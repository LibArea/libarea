<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <a class="right vertical-ind" title="<?= lang('Add'); ?>" href="/admin/badge/add"><i class="icon plus"></i></a>
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
                                        <a title="<?= lang('Edit'); ?>" href="/admin/badge/<?= $bg['badge_id']; ?>/edit">
                                            <i class="icon pencil"></i>
                                        </a>
                                    </span>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="no-content"><?= lang('No'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>