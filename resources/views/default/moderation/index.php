<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>
                <?php if (!empty($moderations)) { ?>
                    <div class="moderations">
                
                        <?php foreach ($moderations as  $mod) { ?> 
                            <div class="post-telo white-box">
                                <div class="post-footer-full small lowercase">
                                    <img class="ava" alt="<?= $mod['login']; ?>" src="<?= user_avatar_url($mod['avatar'], 'small'); ?>">
                                    
                                    <span class="indent"></span>
                                    <a href="/u/<?= $mod['login']; ?>"><?= $mod['login']; ?></a> 
                                        
                                    <span class="indent"></span>
                                    <?= $mod['mod_created_at']; ?>
                                </div>
                                <div>
                                    <a href="/post/<?= $mod['post_id']; ?>/<?= $mod['post_slug']; ?>">
                                        <?= $mod['post_title']; ?>
                                    </a>
                                </div>
                                <div class="small">
                                   <?= lang('Action'); ?>: <?= lang($mod['mod_action']); ?> 
                                </div>     
                            </div>  
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="no-content"><i class="icon info"></i> <?= lang('No moderation logs'); ?>...</div>
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('meta-moderation'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>   