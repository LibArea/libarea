<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="pt5 pb5 flex">
            <div class="box-number mr10">
                <div class="bg-info p10">
                    <a class="box-index" rel="noreferrer gray" href="/admin/posts">
                        <div class="size-13 right">
                            <i class="icon-book-open"></i>
                        </div>
                        <div class="count"><?= $stats['posts_count']; ?></div>
                        <div class="size-15"><?= lang('Posts'); ?></div>
                    </a>
                </div>
            </div>
            <div class="box-number">
                <div class="bg-warning p10 mr10">
                    <a class="box-index" rel="noreferrer gray" href="/admin/answers">
                        <div class="size-13 right">
                            <i class="icon-comment-empty"></i>
                        </div>
                        <div class="count"><?= $stats['answers_count']; ?></div>
                        <div class="size-15"><?= lang('Answers-n'); ?></div>
                    </a>
                </div>
            </div>
            <div class="box-number">
                <div class="bg-danger p10 mr10">
                    <a class="box-index" rel="noreferrer" href="/admin/users">
                        <div class="size-13 right">
                            <i class="icon-user-o icon"></i>
                        </div>
                        <div class="count"><?= $stats['users_count']; ?></div>
                        <div class="size-15"><?= lang('Users'); ?></div>
                    </a>
                </div>
            </div>
            <div class="box-number">
                <div class="bg-success p10">
                    <a class="box-index" rel="noreferrer" href="/admin/comments">
                        <div class="size-13 right">
                            <i class="icon-commenting-o"></i>
                        </div>
                        <div class="count"><?= $stats['comments_count']; ?></div>
                        <div class="size-15"><?= lang('Comments-n'); ?></div>
                    </a>
                </div>
            </div>
        </div>
       
        <div class="white-box mt10">
            <div class="pt5 pr15 pb5 pl15">
                <h4 class="mt5"><?= lang('Useful resources'); ?></h4>
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