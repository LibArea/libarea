<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>
            </div>
        </div>

        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15 flex center">
                <div class="box-number center">
                    <a rel="noreferrer gray" href="/admin/posts">
                        <div class="stat-height">
                            <i class="icon-book-open gray"></i>
                            <sup class="size-13 absolute"><?= $stats['posts_count']; ?></sup>
                        </div>
                        <div class="size-13 gray">
                            <?= lang('Posts'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer gray" href="/admin/answers">
                        <div class="stat-height">
                            <i class="icon-comment-empty gray"></i>
                            <sup class="size-13 gray absolute"><?= $stats['answers_count']; ?></sup>
                        </div>
                        <div class="size-13 gray">
                            <?= lang('Answers-n'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/comments">
                        <div class="stat-height">
                            <i class="icon-commenting-o gray"></i>
                            <sup class="size-13 gray absolute"><?= $stats['comments_count']; ?></sup>
                        </div>
                        <div class="size-13 gray">
                            <?= lang('Comments-n'); ?>
                        </div>
                    </a>
                </div>

                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/users">
                        <div class="stat-height">
                            <i class="icon-user-o red"></i>
                            <sup class="size-13 gray absolute"><?= $stats['users_count']; ?></sup>
                        </div>
                        <div class="size-13 gray">
                            <?= lang('Users'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/spaces">
                        <div class="stat-height">
                            <i class="icon-infinity green"></i>
                            <sup class="size-13 gray absolute"><?= $stats['spaces_count']; ?></sup>
                        </div>
                        <div class="size-13 gray">
                            <?= lang('Spaces'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/topics">
                        <div class="stat-height">
                            <i class="icon-clone brown"></i>
                            <sup class="size-13 gray absolute"><?= $stats['topics_count']; ?></sup>
                        </div>
                        <div class="size-13 gray">
                            <?= lang('Topics'); ?>
                        </div>
                    </a>
                </div>
                <div class="box-number center">
                    <a rel="noreferrer" href="/admin/webs">
                        <div class="stat-height">
                            <i class="icon-link blue"></i>
                            <sup class="size-13 gray absolute"><?= $stats['links_count']; ?></sup>
                        </div>
                        <div class="size-13 gray">
                            <?= lang('Domains'); ?>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h3 class="mt0"><?= lang('Help'); ?>:</h3>
                <i class="icon-record-outline gray-light"></i> <a rel="noreferrer" href="https://loriup.ru">LoriUP.ru</a></br>
                <i class="icon-record-outline gray-light"></i> <a rel="noreferrer" href="https://phphleb.ru/">PHP Micro-Framework HLEB</a></br>
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