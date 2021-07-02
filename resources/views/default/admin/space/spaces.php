<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                    <a class="right" href="/admin/space/add"><?= lang('Add'); ?></a>
                </h1>

                <div class="space">
                    <?php if (!empty($spaces)) { ?>
                  
                        <div class="t-table">
                            <div class="t-th">
                                <span class="t-td center">Id</span>
                                <span class="t-td"><?= lang('Logo'); ?></span>
                                <span class="t-td"><?= lang('Info'); ?></span>
                                <span class="t-td center">Ban</span>
                                <span class="t-td center"><?= lang('Action'); ?></span>
                            </div>

                            <?php foreach ($spaces as $key => $sp) { ?>  
                                <div class="t-tr">
                                    <span class="t-td w-30 center">
                                        <?= $sp['space_id']; ?>
                                    </span>  
                                    <span class="t-td w-30 center">
                                        <img class="space-logo" src="<?= spase_logo_url($sp['space_img'], 'max'); ?>">
                                    </span>
                                    <span class="t-td">
                                        <a title="<?= $sp['space_name']; ?>" href="/s/<?= $sp['space_slug']; ?>">
                                            <?= $sp['space_name']; ?> (s/<?= $sp['space_slug']; ?>)
                                        </a>  
                                        <sup>
                                            <?php if($sp['space_type'] == 1) {  ?>
                                                <span class="red"><?= lang('official'); ?></span>
                                            <?php } else { ?>
                                                <?= lang('All'); ?>   
                                            <?php } ?>
                                        </sup>
                                        
                                        <br>
                                        
                                        <?= $sp['space_description']; ?> <br>
                                        <small>
                                            <?= $sp['space_date']; ?> 
                                            <img class="ava-small" src="<?= user_avatar_url($sp['avatar'], 'small'); ?>">
                                            <a target="_blank" rel="noopener" href="/u/<?= $sp['login']; ?>">
                                                <?= $sp['login']; ?>
                                            </a>
                                        </small>
                                    </span>
                                    <span class="t-td center">
                                        <?php if($sp['space_is_delete']) { ?>
                                            <span class="space-ban" data-id="<?= $sp['space_id']; ?>">
                                                <span class="red"><?= lang('Unban'); ?></span>
                                            </span>
                                        <?php } else { ?>
                                            <span class="space-ban" data-id="<?= $sp['space_id']; ?>"><?= lang('Ban it'); ?></span>
                                        <?php } ?>
                                    </span>
                                    <span class="t-td center">
                                        <a title="<?= lang('Edit'); ?>" href="/space/<?= $sp['space_slug']; ?>/edit">
                                            <i class="icon pencil"></i>
                                        </a>
                                    </span>
                                
                                </div>
                            <?php } ?>
                        </div>
                        * <?= lang('Ban-space-info-posts'); ?>...
                        <div class="pagination">
                      
                        </div>
                        
                    <?php } else { ?>
                        <div class="no-content"><?= lang('No'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>