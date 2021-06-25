<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                    <a class="right" href="/admin/wordadd"><?= lang('Add'); ?></a>
                </h1>
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

                        <div class="pagination">
                      
                        </div>
                        
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