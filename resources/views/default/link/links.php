<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding space-tags">
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
                                    </h2>
                                </a> 
                                <a class="green indent-bid" target="_blank" rel="nofollow noreferrer ugc" href="<?= $link['link_url']; ?>">
                                    <img class="favicon" alt="<?= $link['link_url_domain']; ?>" src="<?= favicon_url($link['link_id']); ?>"> 
                                    <?= $link['link_url_domain']; ?>
                                </a>                                
                                <div class="domain-content indent-bid">
                                    <?php if($link['link_content']) { ?>
                                        <?= $link['link_content']; ?>
                                    <?php } else { ?>
                                        Add content...                                    
                                    <?php } ?> 
                                </div> 
                                <div class="post-footer lowercase">
                                    <?php if (!$uid['id']) { ?> 
                                        <div class="voters">
                                            <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                                            <div class="score">
                                                +<?= $link['link_count']; ?>                
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="voters active">
                                            <div class="up-id"></div>
                                            <div class="score">
                                                +<?= $link['link_count']; ?>                
                                           </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                <?php } else { ?>
                    <div class="no-content"><?= lang('No'); ?>...</div>
                <?php } ?>
            </div>
        </div>
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