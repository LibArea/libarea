<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1 class="top"><?= $data['h1']; ?></h1>

                <?php if (!empty($comments)) { ?>

                    <?php foreach ($comments as $comm) { ?> 
                    
                        <?php if($comm['comment_del'] == 0) { ?>
                            <div class="comm-telo_bottom">
                                <div class="comm-telo">
                                    <div class="comm-header date">
                                        <img class="ava" alt="<?= $comm['login']; ?>" src="<?= user_avatar_url($comm['avatar'], 'small'); ?>">
                                        <span class="user"> 
                                            <a href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                            <?= $comm['date']; ?>
                                        </span> 
                                        <span class="otst"> | </span>
                                        <span>
                                            + <?= $comm['comment_votes']; ?>
                                        </span>
                                        <span class="otst"> | </span>
                                        <span>  
                                           <a href="/post/<?= $comm['post_id']; ?>/<?= $comm['post_slug']; ?>"><?= $comm['post_title']; ?></a>
                                        </span>
                                    </div>
                                    <div class="comm-telo-body">
                                        <?= $comm['comment_content']; ?> 
                                    </div>
                                </div>
                            </div>  
                        <?php } else { ?>    
                            <div class="dell comm-telo_bottom"> 
                                <div class="voters"></div>
                                ~ <?= lang('Comment deleted'); ?>
                            </div>
                        <?php } ?>     
                                
                    <?php } ?>

                <?php } else { ?>
                    <div class="no-content"><?= lang('no-comment'); ?>...</div>
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <?php if ($uid['id'] == 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
        <?php } else { ?>
            <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
        <?php } ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>