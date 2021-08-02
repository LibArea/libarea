<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>
                <div class="all-users">
                    <?php foreach ($users as $ind => $user) { ?>
                        <div class="column">
                            <div class="user_card">
                                <div>
                                    <a href="/u/<?= $user['login']; ?>">
                                        <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'gr small'); ?>
                                    </a>
                                </div>
                                <div class="box-footer size-13">
                                    <a href="/u/<?= $user['login']; ?>"><?= $user['login']; ?></a>
                                    <br>
                                    <?php if ($user['name']) { ?>
                                        <?= $user['name']; ?>
                                    <?php } else { ?>

                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/users'); ?>
    </main>
    <aside>
        <?php if ($uid['id'] == 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
        <?php } else { ?>
            <div class="white-box">
                <div class="inner-padding big">
                    <?= lang('info_users'); ?>
                </div>
            </div>
        <?php } ?>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>