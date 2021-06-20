<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['h1']; ?></span>
                </h1>

                <div class="domains">
                    <?php if (!empty($domains)) { ?>
                  
                        <?php foreach ($domains as $key => $domain) { ?>  
                            <div class="domain-box">
                                <h2 class="title">
                                    <?php if($domain['link_title']) { ?>
                                        <?= $domain['link_title']; ?>
                                    <?php } else { ?>
                                        Add title...                                    
                                    <?php } ?> 
                                </h2>
                                <div class="domain-telo indent-bid">
                                    <?php if($domain['link_content']) { ?>
                                        <?= $domain['link_content']; ?>
                                    <?php } else { ?>
                                        Add content...                                    
                                    <?php } ?> 
                                </div> 
                                <div class="domain-footer indent-bid">
                                    <a class="green" rel="nofollow noreferrer" href="<?= $domain['link_url']; ?>">
                                        <i class="icon share-alt"></i><?= $domain['link_url']; ?>
                                    </a> | 
                                    
                                    <?= $domain['link_url_domain']; ?> |
                                    <?= $domain['link_count']; ?> |
                                    
                                    <?php if($domain['link_is_deleted'] == 0) { ?>
                                        active
                                    <?php } else { ?>
                                        <span class="red">Ban</span>                                  
                                    <?php } ?> |
                                    <a href="/admin/domain/<?= $domain['link_id']; ?>/edit"><?= lang('Edit'); ?></a>
                                </div>
                            </div>
                        <?php } ?>

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