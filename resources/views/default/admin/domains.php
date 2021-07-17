<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                    <a class="right" href="/web/add"><?= lang('Add'); ?></a>
                </h1>

                <div class="domains">
                    <?php if (!empty($domains)) { ?>
                  
                        <?php foreach ($domains as $key => $link) { ?>  
                            <div class="domain-box">
                                <span class="add-favicon right small" data-id="<?= $link['link_id']; ?>">
                                    +фавикон
                                </span>
                                <h2 class="title">
                                    <?php if($link['link_title']) { ?>
                                        <?= $link['link_title']; ?>
                                    <?php } else { ?>
                                        Add title...                                    
                                    <?php } ?> 
                                </h2>
                                <div class="domain-telo indent-bid">
                                    <?php if($link['link_content']) { ?>
                                        <?= $link['link_content']; ?>
                                    <?php } else { ?>
                                        Add content...                                    
                                    <?php } ?> 
                                </div> 
                                <div class="domain-footer indent-bid">
                                    <a class="green" rel="nofollow noreferrer" href="<?= $link['link_url']; ?>">
                                        <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
                                        <?= $link['link_url']; ?>
                                    </a> | 
                                    id<?= $link['link_id']; ?> |
                                    <?= $link['link_url_domain']; ?> |
                                    
                                    <?php if($link['link_is_deleted'] == 0) { ?>
                                        active
                                    <?php } else { ?>
                                        <span class="red">Ban</span>                                  
                                    <?php } ?> |
                                    <a href="/web/edit/<?= $link['link_id']; ?>"><?= lang('Edit'); ?></a>
                                    <span class="right heart-link red">
                                         +<?= $link['link_count']; ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>

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