<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['h1']; ?></span>
                </h1>

                <?php if (!empty($answers)) { ?>
              
                    <?php foreach ($answers as $answ) { ?>  
                    
                        <div class="answ-telo_bottom" id="answ_<?= $answ['comment_id']; ?>">
                            <div class="small">
                                <img class="ava" src="<?= user_avatar_url($answ['avatar'], 'small'); ?>">
                                <a class="date" href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a> 
                                
                                <span class="date"><?= $answ['date']; ?></span>

                                <span class="indent"> &#183; </span>
                                <a href="/post/<?= $answ['post_id']; ?>/<?= $answ['post_slug']; ?>"><?= $answ['post_title']; ?></a>
                            </div>
                            <div class="answ-telo-body">
                                <?= $answ['content']; ?> 
                            </div>
                           <div class="post-full-footer date">
                               + <?= $answ['answer_votes']; ?>
                               <span id="cm_dell" class="right comm_link small">
                                    <a data-id="<?= $answ['answer_id']; ?>" class="recover-answ"><?= lang('Recover'); ?></a>
                               </span>
                           </div>
                        </div>
                    <?php } ?>
                    
                    <div class="pagination">
                  
                    </div>
                    
                <?php } else { ?>
                    <div class="no-content"> <i class="icon info"></i> <?= lang('No answers'); ?>...</div>
                <?php } ?>

            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>  