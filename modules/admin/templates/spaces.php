<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <a class="right" title="<?= lang('Add'); ?>" href="/space/add">
                    <i class="light-icon-plus middle"></i>
                </a>
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

                <ul class="nav-tabs">
                    <?php if ($data['sheet'] == 'allspaces') { ?>
                        <li class="active">
                            <span><?= lang('All'); ?></span>
                        </li>
                        <li>
                            <a href="/admin/spaces/ban">
                                <span><?= lang('Banned'); ?></span>
                            </a>
                        </li>
                    <?php } elseif ($data['sheet'] == 'banspaces') { ?>
                        <li>
                            <a href="/admin/spaces">
                                <span><?= lang('All'); ?></span>
                            </a>
                        </li>
                        <li class="active">
                            <span><?= lang('Banned'); ?></span>
                        </li>
                    <?php } ?>
                </ul>

                <div class="space">
                    <?php if (!empty($spaces)) { ?>

                        <div class="t-table">
                            <div class="t-th">
                                <span class="t-td center">Id</span>
                                <span class="t-td center"><?= lang('Logo'); ?></span>
                                <span class="t-td"><?= lang('Info'); ?></span>
                                <span class="t-td center">Ban</span>
                                <span class="t-td center"><?= lang('Action'); ?></span>
                            </div>

                            <?php foreach ($spaces as $key => $sp) { ?>
                                <div class="t-tr">
                                    <span class="t-td width-30 center">
                                        <?= $sp['space_id']; ?>
                                    </span>
                                    <span class="t-td width-30 center">
                                        <?= spase_logo_img($sp['space_img'], 'max', $sp['space_slug'], 'ava-64'); ?>
                                    </span>
                                    <span class="t-td small">
                                        <a class="title" title="<?= $sp['space_name']; ?>" href="/s/<?= $sp['space_slug']; ?>">
                                            <?= $sp['space_name']; ?> (s/<?= $sp['space_slug']; ?>)
                                        </a>

                                        <sup>
                                            <?php if ($sp['space_type'] == 1) {  ?>
                                                <span class="red"><?= lang('official'); ?></span>
                                            <?php } else { ?>
                                                <?= lang('All'); ?>
                                            <?php } ?>
                                        </sup>

                                        <div class="content-telo">
                                            <?= $sp['space_description']; ?>
                                        </div>

                                        <?= $sp['space_date']; ?>
                                        <span class="indent"> &#183; </span>
                                        <?= user_avatar_img($sp['avatar'], 'small', $sp['login'], 'ava'); ?>
                                        <a target="_blank" rel="noopener" href="/u/<?= $sp['login']; ?>">
                                            <?= $sp['login']; ?>
                                        </a>

                                    </span>
                                    <span class="t-td center">
                                        <?php if ($sp['space_is_delete']) { ?>
                                            <span class="space-ban" data-id="<?= $sp['space_id']; ?>">
                                                <span class="red"><?= lang('Unban'); ?></span>
                                            </span>
                                        <?php } else { ?>
                                            <span class="space-ban" data-id="<?= $sp['space_id']; ?>"><?= lang('Ban it'); ?></span>
                                        <?php } ?>
                                    </span>
                                    <span class="t-td center">
                                        <a title="<?= lang('Edit'); ?>" href="/space/edit/<?= $sp['space_id']; ?>">
                                            <i class="light-icon-edit middle"></i>
                                        </a>
                                    </span>

                                </div>
                            <?php } ?>
                        </div>
                        * <?= lang('Ban-space-info-posts'); ?>...

                    <?php } else { ?>
                        <div class="no-content gray">
                            <i class="light-icon-info-square green middle"></i>
                            <span class="middle"><?= lang('No'); ?>...</span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/spaces'); ?>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>