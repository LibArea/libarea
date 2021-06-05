<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">

        <h1 class="top"><?= $data['h1']; ?></h1>

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
                                    <img class="ava" src="<?= user_avatar_url($comm['avatar'], 'small'); ?>">
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
                
           <?php if(!($data['pNum'] > $data['pagesCount'])) { ?>
                <div class="pagination">   
                    <?php if($data['pNum'] != 1) { ?> 
                        <a href="/comments/<?= $data['pNum'] - 1; ?>"> << <?= lang('Page'); ?> <?= $data['pNum'] - 1; ?></a> 
                    <?php } ?>
                    <?php if($data['pagesCount'] != $data['pNum'] && $data['pNum'] != 1) { ?>|<?php } ?> 
                    <?php if($data['pagesCount'] > $data['pNum']) { ?>
                        <a href="/comments/<?= $data['pNum'] + 1; ?>"><?= lang('Page'); ?>  <?= $data['pNum'] + 1; ?> >></a> 
                    <?php } ?>
                </div>
            <?php } ?>
                
            <?php } else { ?>
                <div class="no-content"><?= lang('no-comment'); ?>...</div>
            <?php } ?>
        </div> 
    </main>
    <aside>
        <?php if ($uid['id'] == 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
        <?php } ?>    
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>   