<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?> - IP</span>
                </h1>
                
                <table>
                    <thead>
                        <th>N</th>
                        <th><?= lang('Information'); ?></th>
                        <th>E-mail</th>
                        <th><?= lang('Sign up'); ?></th>
                        <th>IP <?= lang('registrations'); ?></th>
                        <th>Ban</th>
                        <th><?= lang('Action'); ?></th>
                    </thead>
                <?php foreach ($alluser as $user) {  ?>
                         <tr>
                            <td class="width-30 center">
                                <?= $user['user_id']; ?>
                            </td>
                            <td>
                                <?= user_avatar_img($user['user_avatar'], 'small', $user['user_login'], 'ava'); ?>
                                <a href="/u/<?= $user['user_login']; ?>"><?= $user['user_login']; ?></a>
                                <?php if ($user['user_name']) { ?>
                                    (<?= $user['user_name']; ?>)
                                <?php } ?>
                                <sup class="red">TL:<?= $user['user_trust_level']; ?></sup>
                                <?php if ($user['user_invitation_id'] != 0) { ?><sup>+ inv. id<?= $user['user_invitation_id']; ?></sup><?php } ?> <br>
                            </td>
                            <td>
                                <span class="date"><?= $user['user_email']; ?></span>
                            </td>
                            <td>
                                <?= $user['user_created_at']; ?>
                            </td>
                            <td>
                                <?= $user['user_reg_ip']; ?> <?php if ($user['replayIp'] > 1) { ?>
                                    <sup class="red">(<?= $user['replayIp']; ?>)</sup>
                                <?php } ?>
                            </td>
                            <td class="center">
                                <?php if ($user['user_trust_level'] != 5) { ?>
                                    <?php if ($user['isBan']) { ?>
                                        <div class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                                            <span class="red"><?= lang('Unban'); ?></span>
                                        </div>
                                    <?php } else { ?>
                                        <div class="type-ban" data-id="<?= $user['user_id']; ?>" data-type="user">
                                            <?= lang('Ban it'); ?>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    ---
                                <?php } ?>
                            </td>
                            <td class="center">
                                <?php if ($user['user_trust_level'] != 5) { ?>
                                    <a title="<?= lang('Edit'); ?>" href="/admin/user/<?= $user['user_id']; ?>/edit">
                                        <i class="icon-pencil size-15"></i>
                                    </a>
                                <?php } else { ?>
                                    ---
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>