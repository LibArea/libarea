<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['login'], lang('Profile'), $data['h1']); ?>

                <?php if (!empty($data['messages'])) { ?>

                    <?php foreach ($data['messages'] as  $msg) { ?>

                        <div class="hidden mb15<?php if (!$msg['unread'] > 0) { ?> active-notif<?php } ?>">
                            <div class="size-13 flex">
                                <?php if ($msg['sender_uid'] == $uid['id']) {  ?>
                                    <?= lang('You'); ?>
                                    <span class="mr5 ml5"></span>
                                    <?= lang_date($msg['update_time']); ?>
                                <?php } else { ?>
                                    <?= lang('From'); ?>
                                    <span class="mr5 ml5"></span>
                                    <?= user_avatar_img($msg['msg_user']['avatar'], 'small', $msg['msg_user']['login'], 'ava'); ?>
                                    <span class="mr5 ml5"></span>
                                    <a href="/u/<?= $msg['msg_user']['login']; ?>">
                                        <?= $msg['msg_user']['login']; ?>
                                    </a>
                                    <span class="ml15"></span>
                                    <span class="gray lowercase">
                                        <?= lang_date($msg['update_time']); ?>
                                    </span>
                                <?php } ?>
                            </div>
                            <div class="message one gray">
                                <?= $msg['message']['message']; ?>
                            </div>

                            <a class="lowercase size-13 right" href="/messages/read/<?= $msg['id']; ?>">
                                <?php if ($msg['unread']) { ?>
                                    <?= lang('There are'); ?> <?= $msg['count']; ?> <?= $msg['unread_num']; ?>
                                <?php } else { ?>
                                    <span class="red"><?= lang('View'); ?></span>
                                    <?php if ($msg['count'] != 0) { ?>
                                        <?= $msg['count']; ?> <?= $msg['count_num']; ?>
                                    <?php } ?>
                                <?php } ?>
                            </a>

                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <?= no_content('No dialogs'); ?>
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('Under development'); ?>...
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>