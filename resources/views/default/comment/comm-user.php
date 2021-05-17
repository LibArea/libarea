<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <h1 class="top"><?= $data['h1']; ?></h1>

    <?php if (!empty($comments)) { ?>

        <?php foreach ($comments as $comm) { ?> 
        
            <?php if($comm['comment_del'] == 0) { ?>
                <div class="comm-telo_bottom">
                    <div class="voters">
                        <div class="comm-up-id"></div>
                        <div class="score"><?= $comm['comment_votes']; ?></div>
                    </div>

                    <div class="comm-telo">
                        <div class="comm-header">
                            <img class="ava" alt="<?= $comm['login']; ?>" src="/uploads/users/avatars/small/<?= $comm['avatar']; ?>">
                            <span class="user"> 
                                <a href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                
                                <?= $comm['date']; ?>
                            </span> 
         
                            <span class="otst"> | </span>
                            <span class="date">  
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
</main> 
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>