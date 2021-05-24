<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<main class="admin">
    <a class="right" href="/admin/badge/add"><?= lang('Add'); ?></a>
    <h1 class="top">
        <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['h1']; ?></span>
    </h1>

    <div class="badges">
        <?php if (!empty($badges)) { ?>
      
            <div class="t-table">
                <div class="t-th">
                    <span class="t-td center">Id</span>
                    <span class="t-td">Иконка</span>
                    <span class="t-td">Название&nbsp;/&nbsp;Описание</span>
                    <span class="t-td center">Действие</span>
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
                            <a href="/admin/badge/<?= $bg['badge_id']; ?>/edit">Изменить</a>
                        </span>
                    </div>
                <?php } ?>
            </div>

            <div class="pagination">
          
            </div>
            
        <?php } else { ?>
            <div class="no-content"><?= lang('no-comment'); ?>...</div>
        <?php } ?>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?> 