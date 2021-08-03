<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>
            </div>
        </div>

        <div class="white-box">
            <div class="inner-padding flex center">
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/posts">
                        <div class="stat-height">
                            <i class="light-icon-book gray"></i><sup><?= $stats['posts_count']; ?></sup>
                        </div>
                        <div class="size-13">
                            <?= lang('Posts'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/answers">
                        <div class="stat-height">
                            <i class="light-icon-message gray"></i><sup><?= $stats['answers_count']; ?></sup>
                        </div>
                        <div class="size-13">
                            <?= lang('Answers-n'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/comments">
                        <div class="stat-height">
                            <i class="light-icon-messages gray"></i><sup><?= $stats['comments_count']; ?></sup>
                        </div>
                        <div class="size-13">
                            <?= lang('Comments-n'); ?>
                        </div>
                    </a>
                </div>

                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/users">
                        <div class="stat-height">
                            <i class="light-icon-users red"></i><sup><?= $stats['users_count']; ?></sup>
                        </div>
                        <div class="size-13">
                            <?= lang('Users'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/spaces">
                        <div class="stat-height">
                            <i class="light-icon-infinity green"></i><sup><?= $stats['spaces_count']; ?></sup>
                        </div>
                        <div class="size-13">
                            <?= lang('Spaces'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/topics">
                        <div class="stat-height">
                            <i class="light-icon-layers-subtract brown"></i><sup><?= $stats['topics_count']; ?></sup>
                        </div>
                        <div class="size-13">
                            <?= lang('Topics'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/webs">
                        <div class="stat-height">
                            <i class="light-icon-link blue"></i><sup><?= $stats['links_count']; ?></sup>
                        </div>
                        <div class="size-13">
                            <?= lang('Domains'); ?>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="white-box">
            <div class="inner-padding">

                <h3><?= lang('Help'); ?>:</h3>
                <i class="light-icon-point middle"></i> <a rel="noreferrer" href="https://loriup.ru">LoriUP.ru</a></br>
                <i class="light-icon-point middle"></i> <a rel="noreferrer" href="https://phphleb.ru/">PHP Micro-Framework HLEB</a></br>
                </ul>
                <hr>
                <div class="boxline">
                    <label for="name">PC:</label>
                    <?= php_uname('s'); ?> <?php echo php_uname('r'); ?>
                </div>
                <div class="boxline">
                    <label for="name">PHP:</label>
                    <?= PHP_VERSION; ?>
                </div>
                <div class="boxline">
                    <label for="name"><?= lang('Freely'); ?>:</label>
                    <?= $data['bytes']; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>