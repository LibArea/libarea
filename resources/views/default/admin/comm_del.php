<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['h1']; ?></span>
                </h1>

                <?php if (!empty($comments)) { ?>
              
                    <?php foreach ($comments as $comm) { ?>  
                    
                        <div class="comm-telo_bottom" id="comm_<?= $comm['comment_id']; ?>">
                            <div class="small">
                                <img class="ava" src="<?= user_avatar_url($comm['avatar'], 'small'); ?>">
                                <a class="date" href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                
                                <span class="date"><?= $comm['date']; ?></span>

                                <span class="indent"> &#183; </span>
                                <a href="/post/<?= $comm['post_id']; ?>/<?= $comm['post_slug']; ?>"><?= $comm['post_title']; ?></a>
                            </div>
                            <div class="comm-telo-body">
                                <?= $comm['content']; ?> 
                            </div>
                           <div class="post-full-footer date">
                               + <?= $comm['comment_votes']; ?>
                               <span id="cm_dell" class="right comm_link small">
                                    <a data-id="<?= $comm['comment_id']; ?>" class="recover-comm"><?= lang('Recover'); ?></a>
                               </span>
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
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>  