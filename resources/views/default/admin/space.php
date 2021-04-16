<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<div class="w-100">
    <div class="left-ots">
        <a class="right" href="/admin/space/add"><?= lang('Add'); ?></a>
        <h1 class="top"><?php echo $data['h1']; ?></h1>

        <div class="space">
            <?php if (!empty($space)) { ?>
          
                <div class="t-table">
                    <div class="t-th">
                        <span class="t-td center">Id</span>
                        <span class="t-td">Логотип</span>
                        <span class="t-td">Имя / slug</span>
                        <span class="t-td">Описание</span>
                        <span class="t-td center">Действие</span>
                    </div>

                    <?php foreach ($space as $key => $sp) { ?>  
                        <div class="t-tr">
                            <span class="t-td w-30 center">
                                <?= $sp['space_id']; ?>
                            </span>  
                            <span class="t-td w-30 center">
                                <img class="ava" alt="Evg" src="/uploads/space/<?= $sp['space_img']; ?>">
                            </span>
                            <span class="t-td">
                                <?php if($sp['hidden_space_id'] >= 1) {  ?>
                                    <span class="red">&#10003;</span>
                                <?php } ?>
                            
                                <span class="space-color space_<?= $sp['space_color'] ?>"></span>
                                <a title="<?= $sp['space_name']; ?>" class="space-u" href="/s/<?= $sp['space_slug']; ?>">
                                    <?= $sp['space_name']; ?> (<?= $sp['space_slug']; ?>)
                                </a> 
                                 
                            </span>
                            <span class="t-td">
                                <?= $sp['space_description']; ?> <br>
                                <?= $sp['space_date']; ?>
                            </span>
                            <span class="t-td center">
                                <?php if($sp['space_is_delete']) { ?>
                                    <span class="space-ban" data-id="<?= $sp['space_id']; ?>">
                                        <span class="red">разбанить</span>
                                    <span>
                                <?php } else { ?>
                                    <span class="space-ban" data-id="<?= $sp['space_id']; ?>">забанить<span>
                                <?php } ?>

                               <a href="/space/<?= $sp['space_slug']; ?>/edit">изменить</a>
                            </span>
                        
                        </div>
                    <?php } ?>
                </div>
                * Бан пространства повлечет за собой недоступность всех постов...
                <div class="pagination">
              
                </div>
                
            <?php } else { ?>
                <div class="no-content"><?= lang('no-comment'); ?>...</div>
            <?php } ?>
        </div> 
     </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>   