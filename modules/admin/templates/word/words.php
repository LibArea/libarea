<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <a class="right" title="<?= lang('Add'); ?>" href="/admin/words/add">
                    <i class="icon-plus middle"></i>
                </a>
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

                <div class="words">
                    <?php if (!empty($words)) { ?>

                        <?php foreach ($words as $key => $word) { ?>
                            <div class="content-telo">
                                <?= $word['stop_word']; ?> |
                                <a data-id="<?= $word['stop_id']; ?>" data-type="word" class="type-ban lowercase size-13">
                                    <?= lang('Remove'); ?>
                                </a>
                            </div>
                        <?php } ?>

                    <?php } else { ?>
                        <div class="no-content"><?= lang('Stop words no'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>