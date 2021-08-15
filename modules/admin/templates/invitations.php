<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

                <div class="telo invitations">
                    <?php if (!empty($invitations)) { ?>

                        <?php foreach ($invitations as $key => $inv) { ?>
                            <div class="content-telo">
                                <a href="/u/<?= $inv['uid']['user_login']; ?>"><?= $inv['uid']['user_login']; ?></a> <sup>id<?= $inv['uid']['user_id']; ?></sup>
                                =>
                                <a href="/u/<?= $inv['user_login']; ?>"><?= $inv['user_login']; ?></a> <sup>id<?= $inv['active_uid']; ?></sup>

                                <span class="size-13"> - <?= $inv['active_time']; ?></span>
                            </div>
                        <?php } ?>

                    <?php } else { ?>
                        <div class="no-content"><?= lang('There are no comments'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>