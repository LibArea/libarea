<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1 class="top">
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['h1']; ?></span>
                </h1>

                <div class="telo comments">
                    <?php if (!empty($comments)) { ?>
                  
                        <?php foreach ($comments as $comm) { ?>  
                        
                            <div class="comm-telo_bottom" id="comm_<?= $comm['comment_id']; ?>">

                                <div class="voters">
                                    <div class="comm-up-id"></div>
                                    <div class="score"><?= $comm['comment_votes']; ?></div>
                                </div>
                                
                                <div class="comm-telo">
                                    <div class="comm-header">
                                        <img class="ava" src="<?= user_avatar_url($comm['avatar'], 'small'); ?>">
                                        <span class="user"> 
                                            <a href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                            <?= $comm['date']; ?>
                                        </span> 
                     
                                        <span class="otst"> | </span>
                                        <span class="date">  
                                            <a href="/post/<?= $comm['post_slug']; ?>"><?= $comm['post_title']; ?></a>
                                        </span>
                                        <span class="otst"> | </span>
                                        <span id="cm_dell" class="comm_link">
                                            <a data-id="<?= $comm['comment_id']; ?>" class="recover-comm"><?= lang('Recover'); ?></a>
                                        </span>
                                    </div>
                                    <div class="comm-telo-body">
                                        <?= $comm['content']; ?> 
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        
                        <div class="pagination">
                      
                        </div>
                        
                    <?php } else { ?>
                        <div class="no-content"><?= lang('no-comment'); ?>...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>  