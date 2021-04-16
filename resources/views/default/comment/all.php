<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/_block/left-menu.php'; ?>
<main>

    <h1 class="top"><?php echo $data['h1']; ?></h1>

    <div class="telo comments">
        <?php if (!empty($comments)) { ?>
      
            <?php foreach ($comments as $comm) { ?>  
            
                <?php if($comm['comment_del'] == 0) { ?>
                    <div class="comm-telo_bottom">
                        
                        <?php if (!$uid['id']) { ?> 
                            <div class="voters">
                                <a rel="nofollow" href="/login"><div class="comm-up-id"></div></a>
                                <div class="score"><?= $comm['comment_votes']; ?></div>
                            </div>
                        <?php } else { ?>
                            <?php if ($comm['comm_vote_status'] || $uid['id'] == $comm['comment_user_id']) { ?>
                                <div class="voters active">
                                    <div class="comm-up-id"></div>
                                    <div class="score"><?= $comm['comment_votes']; ?></div>
                                </div>
                            <?php } else { ?>
                                <div id="up<?= $comm['comment_id']; ?>" class="voters">
                                    <div data-id="<?= $comm['comment_id']; ?>" class="comm-up-id"></div>
                                    <div class="score"><?= $comm['comment_votes']; ?></div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        
                        <div class="comm-telo">
                            <div class="comm-header">
                                <img class="ava" src="/uploads/avatar/small/<?php echo $comm['avatar'] ?>">
                                <span class="user"> 
                                    <a href="/u/<?php echo $comm['login']; ?>"><?php echo $comm['login']; ?></a> 
                                    
                                    <?php echo $comm['date']; ?>
                                </span> 
             
                                <span class="otst"> | </span>
                                <span class="date">  
                                   <a href="/posts/<?php echo $comm['post_slug']; ?>"><?php echo $comm['post_title']; ?></a>
                                </span>
                            </div>
                            <div class="comm-telo-body">
                                <?php echo $comm['content']; ?> 
                            </div>
                        </div>
                    </div>
                <?php } else { ?>    
                    <div class="dell comm-telo_bottom"> 
                        <div class="voters"></div>
                        ~ Комментарий удален
                    </div>
                <?php } ?> 
            <?php } ?>
            
            <div class="pagination">
          
            </div>
            
        <?php } else { ?>
            <div class="no-content"><?= lang('no-comment'); ?>...</div>
        <?php } ?>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>   