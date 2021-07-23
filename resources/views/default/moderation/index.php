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
                                    <a href="/u/<?= $mod['login']; ?>">
                                        <?= user_avatar_img($mod['avatar'], 'small', $mod['login'], 'ava'); ?>
                                        <span class="indent">
                                            <?= $mod['login']; ?>
                                        </span>
                                    </a> 
                                    <span class="indent">
                                        <?= $mod['mod_created_at']; ?>
                                    </span>
                                </div>
                                <div>
                                    <a href="/post/<?= $mod['post_id']; ?>/<?= $mod['post_slug']; ?>">
                                        <?= $mod['post_title']; ?>
                                    </a>
                                    <?php if($mod['post_type'] == 1) { ?> 
                                        <i class="light-icon-language green"></i>
                                    <?php } ?>
                                </div>
                                <div class="small">
                                   <?= lang('Action'); ?>: <b><?= lang($mod['mod_action']); ?></b> 
                                </div>     
                            </div>  
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <p class="no-content gray">
                        <i class="light-icon-info-square middle"></i> 
                        <span class="middle"><?= lang('No moderation logs'); ?>...</span>
                    </p>
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