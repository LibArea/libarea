<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <?php if ($uid['trust_level'] == 5) { ?>
                    <a title="<?= lang('Add'); ?>" class="right vertical-ind" href="/web/add">
                        <i class="icon plus"></i>
                    </a>
                <?php } ?>    
                <h1><?= $data['h1']; ?></h1>
                
                <?php if (!empty($links)) { ?>
                        <?php foreach ($links as $key => $link) { ?>  
                            <div class="domain-telo">
                                <a href="/domain/<?= $link['link_url_domain']; ?>">
                                    <h2 class="title">
                                        <?php if($link['link_title']) { ?>
                                            <?= $link['link_title']; ?>
                                        <?php } else { ?>
                                            Add title...                                    
                                        <?php } ?> 
                                        <?php if($uid['trust_level'] == 5) { ?>
                                            <span class="indent"></span>
                                            <a class="small" title="<?= lang('Edit'); ?>" href="/web/edit/<?= $link['link_id']; ?>">
                                                <i class="icon pencil"></i>
                                            </a>
                                        <?php } ?>
                                    </h2>
                                </a> 
                                <span class="green indent-big">
                                    <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
                                    <?= $link['link_url_domain']; ?>
                                </span>                                
                                <div class="gray indent-big">
                                    <?php if($link['link_content']) { ?>
                                        <?= $link['link_content']; ?>
                                    <?php } else { ?>
                                        Add content...                                    
                                    <?php } ?> 
                                </div> 
                                <div class="post-footer lowercase">
                                    <?= votes($uid['id'], $link, 'link'); ?> 
                                </div>
                            </div>
                        <?php } ?>
                <?php } else { ?>
                    <div class="no-content"><?= lang('No'); ?>...</div>
                <?php } ?>
            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/domains'); ?>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <?= lang('domains-desc'); ?>.
            </div>                        
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 