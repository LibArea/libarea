<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <a class="right vertical-ind" title="<?= lang('Add'); ?>" href="/admin/wordadd"><i class="icon plus"></i></a>
                <?= breadcrumb('/admin', lang('Admin'), null, null, $data['meta_title']); ?>

                <div class="words">
                    <?php if (!empty($words)) { ?>
                  
                        <?php foreach ($words as $key => $word) { ?>  
                            <div class="small">
                                <?= $word['stop_word']; ?> | 
                                <a data-id="<?= $word['stop_id']; ?>" class="delete-word lowercase">
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
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>