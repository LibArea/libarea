<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <?php if($link['link_title']) { ?>
                    <div class="right heart-link">
                        <?= votes($uid['id'], $link, 'link'); ?> 
                    </div>
                    <h1 class="domain"><?= $link['link_title']; ?>
                        <?php if($uid['trust_level'] > 4) { ?>
                            <span class="indent"></span>
                            <a class="small" title="<?= lang('Edit'); ?>" href="/web/edit/<?= $link['link_id']; ?>">
                                <i class="light-icon-edit middle"></i>
                            </a>
                        <?php } ?>
                    </h1>
                    <div class="gray">
                    <?= $link['link_content']; ?>
                    </div>
                    <div class="domain-footer-small">
                        <a class="green" rel="nofollow noreferrer ugc" href="<?= $link['link_url']; ?>">
                            <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
                            <?= $link['link_url']; ?>
                        </a> 
                        
                        <span class="right"><?= $link['link_count']; ?></span>
                    </div>
                <?php } else { ?>
                    <h1><?= $data['h1']; ?></h1>
                <?php } ?>
            </div>
        </div>
        
        <?php include TEMPLATE_DIR . '/_block/post.php'; ?>

        <?= pagination($data['pNum'], $data['pagesCount'], null, '/domain/' . $link['link_url_domain']); ?>
         
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <?php if (!empty($domains)) { ?>
                    <div class="bar-title small"><?= lang('Domains'); ?></div>
                    <?php foreach ($domains as  $domain) { ?>
                        <a class="date small gray" href="/domain/<?= $domain['link_url_domain']; ?>">
                            <i class="light-icon-link middle"></i> <?= $domain['link_url_domain']; ?> 
                            <sup class="date small"><?= $domain['link_count']; ?></sup>
                        </a><br>
                    <?php } ?>
                <?php } else { ?>
                    <p><?= lang('There are no domains'); ?>...</p>
                <?php } ?>
            </div>                        
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 