<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <div class="left-ots">

        <h1 class="top"><?php echo $data['h1']; ?></h1>

        <?php if (!empty($comments)) { ?>
   
            <?php foreach ($comments as $comm) { ?> 
            
                <?php if($comm['comment_del'] == 0) { ?>
                    <div class="comm-telo_bottom">
                        <div class="voters">
                            <div class="comm-up-id"></div>
                            <div class="score"><?php echo $comm['comment_votes']; ?></div>
                        </div>

                        <div class="comm-telo">
                            <div class="comm-header">
                                <img class="ava" alt="<?php echo $comm['login']; ?>" src="/uploads/avatar/small/<?php echo $comm['avatar']; ?>">
                                <span class="user"> 
                                    <a href="/u/<?php echo $comm['login']; ?>"><?php echo $comm['login']; ?></a> 
                                    
                                    <?php echo $comm['date']; ?>
                                </span> 
             
                                <span class="otst"> | </span>
                                <span class="date">  
                                   <a href="/posts/<?= $comm['post_slug']; ?>"><?php echo $comm['post_title']; ?></a>
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

        <?php } else { ?>
            <div class="no-content"><?= lang('no-comment'); ?>...</div>
        <?php } ?>
    </div>    
</main> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>