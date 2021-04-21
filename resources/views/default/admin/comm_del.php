<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<div class="w-100">
    <h1 class="top"><?php echo $data['h1']; ?></h1>

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
                            <img class="ava" src="/uploads/avatar/small/<?php echo $comm['avatar'] ?>">
                            <span class="user"> 
                                <a href="/u/<?php echo $comm['login']; ?>"><?php echo $comm['login']; ?></a> 
                                <?php echo $comm['date']; ?>
                            </span> 
         
                            <span class="otst"> | </span>
                            <span class="date">  
                                <a href="/posts/<?php echo $comm['post_slug']; ?>"><?php echo $comm['post_title']; ?></a>
                            </span>
                            <span class="otst"> | </span>
                            <span id="cm_dell" class="comm_link">
                                <a data-id="<?= $comm['comment_id']; ?>" class="recover-comm">Восстановить</a>
                            </span>
                        </div>
                        <div class="comm-telo-body">
                            <?php echo $comm['content']; ?> 
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
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?>   