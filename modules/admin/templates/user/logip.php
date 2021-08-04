<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?> - IP</span>
                </h1>

                <div class="t-table">
                    <div class="t-th">
                        <span class="t-td center">N</span>
                        <span class="t-td"><?= lang('Information'); ?></span>
                        <span class="t-td">E-mail</span>
                        <span class="t-td"><?= lang('Sign up'); ?></span>
                        <span class="t-td">IP <?= lang('registrations'); ?></span>
                        <span class="t-td center">Ban</span>
                        <span class="t-td center"><?= lang('Action'); ?></span>
                    </div>
                    <?php foreach ($alluser as $user) {  ?>
                        <div class="t-tr">
                            <span class="t-td width-30 center">
                                <?= $user['id']; ?>
                            </span>
                            <span class="t-td">
                                <?= user_avatar_img($user['avatar'], 'small', $user['login'], 'ava'); ?>
                                <a href="/u/<?= $user['login']; ?>"><?= $user['login']; ?></a>
                                <?php if ($user['name']) { ?>
                                    (<?= $user['name']; ?>)
                                <?php } ?>
                                <sup class="red">TL:<?= $user['trust_level']; ?></sup>
                                <?php if ($user['invitation_id'] != 0) { ?><sup>+ inv. id<?= $user['invitation_id']; ?></sup><?php } ?> <br>
                            </span>
                            <span class="t-td">
                                <span class="date"><?= $user['email']; ?></span>
                            </span>
                            <span class="t-td">
                                <?= $user['created_at']; ?>
                            </span>
                            <span class="t-td">
                                <?= $user['reg_ip']; ?> <?php if ($user['replayIp'] > 1) { ?>
                                    <sup class="red">(<?= $user['replayIp']; ?>)</sup>
                                <?php } ?>
                            </span>
                            <span class="t-td center">
                                <?php if ($user['trust_level'] != 5) { ?>
                                    <?php if ($user['isBan']) { ?>
                                        <div class="user-ban" data-id="<?= $user['id']; ?>">
                                            <span class="red"><?= lang('Unban'); ?></span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="user-ban" data-id="<?= $user['id']; ?>"><?= lang('Ban it'); ?></div>
                                    <?php } ?>
                                <?php } else { ?>
                                    ---
                                <?php } ?>
                            </span>
                            <span class="t-td center">
                                <?php if ($user['trust_level'] != 5) { ?>
                                    <a title="<?= lang('Edit'); ?>" href="/admin/user/<?= $user['id']; ?>/edit">
                                        <i class="light-icon-edit middle"></i>
                                    </a>
                                <?php } else { ?>
                                    ---
                                <?php } ?>
                            </span>

                            <br><br>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>