<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <?php if($uid['id'] > 0) { ?>
                    <?php if($add_space_button === true) { ?>
                        <a title="<?= lang('To create'); ?>" class="right vertical-ind small" href="/space/add">
                            <i class="light-icon-plus red"></i> 
                        </a>
                    <?php } ?> 
                <?php } ?>
            
                <h1><?= $data['h1']; ?></h1>
                
                <ul class="nav-tabs">
                    <?php if($data['sheet'] == 'spaces') { ?>
                        <li class="active">
                            <span><?= lang('All'); ?></span>
                        </li>
                        <?php if($uid['id'] > 0) { ?>
                            <li>
                                <a href="/space/my">
                                    <span><?= lang('Signed'); ?></span>
                                </a>
                            </li>
                         <?php } ?>    
                    <?php } else { ?>
                        <li>
                            <a href="/spaces">
                                <span><?= lang('All'); ?></span>
                            </a>
                        </li>
                        <?php if($uid['id'] > 0) { ?>
                            <li class="active">
                                <span><?= lang('Signed'); ?></span>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>

                <?php if (!empty($space)) { ?>
                    <div class="oblong-box-list space-box-list">
                    <?php foreach ($space as  $sp) { ?>  
                        <div class="oblong-box">
                            <a title="<?= $sp['space_name']; ?>" class="img-box" href="/s/<?= $sp['space_slug']; ?>">
                                <?= spase_logo_img($sp['space_img'], 'max', $sp['space_name'], 'ava-54'); ?>
                            </a>
                            <?php if($sp['space_user_id'] == $uid['id']) { ?>
                                <div class="my_space all"></div>
                            <?php } ?>
                            
                            <span class="space-name"> 
                                <a title="<?= $sp['space_name']; ?>" class="space-s" href="/s/<?= $sp['space_slug']; ?>">
                                    <?= $sp['space_name']; ?>
                                </a> 
                            </span> 
                            
                            <?php if($sp['space_type'] == 1) { ?>
                                 <small><span class="red">— <?= lang('official'); ?></span></small> 
                            <?php } ?>
                            
                            <?php if($sp['space_id'] != 1) { ?>
                                <sup>+ <?= $sp['users'] ?></sup>
                            <?php } ?>

                            <?php if(!$uid['id']) { ?> 
                                <div class="right"> 
                                    <a href="/login"><div class="hide-space-id yes-space">+ <?= lang('Read'); ?></div></a>
                                </div>
                            <?php } else { ?>
                                <?php if($sp['space_id'] !=1) { ?>
                                    <?php if($sp['space_user_id'] != $uid['id']) { ?>
                                        <div class="right"> 
                                            <?php if($sp['signed_space_id'] >= 1) { ?>
                                                <div data-id="<?= $sp['space_id']; ?>" class="hide-space-id no-space">
                                                    <?= lang('Unsubscribe'); ?>
                                                </div>
                                            <?php } else { ?> 
                                                <div data-id="<?= $sp['space_id']; ?>" class="hide-space-id yes-space">
                                                    + <?= lang('Read'); ?>
                                                </div>
                                            <?php } ?>   
                                        </div> 
                                    <?php } ?>    
                                <?php } ?>                        
                            <?php } ?> 

                            <div class="small">
                                <?php if($sp['space_description']) { ?> 
                                    <?= $sp['space_description']; ?> 
                                <?php } else { ?> 
                                    <?= lang('Description is formed'); ?>
                                <?php } ?>    
                            </div>

                        </div>
                    <?php } ?>
                    </div>
                <?php } else { ?>
                    <p class="no-content gray">
                        <i class="light-icon-info-square middle"></i> 
                        <span class="middle"><?= lang('No spaces'); ?>...</span>
                    </p>
                <?php } ?>
            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/spaces'); ?>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?php if($data['sheet'] == 'spaces') { ?>
                    <?= lang('info_space'); ?>
                <?php } else { ?>
                    <?= lang('my_info_space'); ?>
                <?php } ?>
            </div>
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>        