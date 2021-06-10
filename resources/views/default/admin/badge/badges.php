<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <h1 class="top">
            <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['h1']; ?></span>
            <a class="right" href="/admin/badge/add"><?= lang('Add'); ?></a>
        </h1>

        <div class="badges">
            <?php if (!empty($badges)) { ?>
          
                <div class="t-table">
                    <div class="t-th">
                        <span class="t-td center">Id</span>
                        <span class="t-td center">Icon</span>
                        <span class="t-td"><?= lang('Title'); ?>&nbsp;/&nbsp;<?= lang('Description'); ?></span>
                        <span class="t-td center"><?= lang('Action'); ?></span>
                    </div>

                    <?php foreach ($badges as $key => $bg) { ?>  
                        <div class="t-tr">
                            <span class="t-td w-30 center">
                                <?= $bg['badge_id']; ?>
                            </span>  
                            <span class="t-td w-30 center">
                                <?= $bg['badge_icon']; ?>
                            </span>
                            <span class="t-td">
                                <b><?= $bg['badge_title']; ?></b> 
                                <br>
                                <?= $bg['badge_description']; ?>
                            </span>
                            <span class="t-td center">
                                <a title="<?= lang('Edit'); ?>" href="/admin/badge/<?= $bg['badge_id']; ?>/edit">
                                    <i class="icon pencil"></i>
                                </a>
                            </span>
                        </div>
                    <?php } ?>
                </div>

                <div class="pagination">
              
                </div>
                
            <?php } else { ?>
                <div class="no-content"><?= lang('No'); ?>...</div>
            <?php } ?>
        </div> 
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>