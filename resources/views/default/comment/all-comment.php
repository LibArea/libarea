<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <?php if (!empty($comments)) { ?>
              
                    <?php foreach ($comments as $comm) { ?>  
                    
                        <?php if($comm['comment_del'] == 0) { ?>
                            <div class="comm-telo_bottom">
                                <div class="small">
                                    <img class="ava" src="<?= user_avatar_url($comm['avatar'], 'small'); ?>">
                                    <a class="date" href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                     
                                    <span class="date"><?= $comm['date']; ?></span>
                                    
                                    <span class="indent"> &#183; </span>
                                    <a href="/post/<?= $comm['post_id']; ?>/<?= $comm['post_slug']; ?>"><?= $comm['post_title']; ?></a>
                                </div>

                                <div class="comm-telo-body">
                                    <?= $comm['comment_content']; ?> 
                                </div>
                           
                                <div class="post-full-footer date">
                                    <?php if (!$uid['id']) { ?> 
                                        <div class="voters">
                                            <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                                            <div class="score"><?= $comm['comment_votes']; ?></div>
                                        </div>
                                    <?php } else { ?>
                                        <?php if ($comm['comment_vote_status'] || $uid['id'] == $comm['comment_user_id']) { ?>
                                            <div class="voters active">
                                                <div class="up-id"></div>
                                                <div class="score"><?= $comm['comment_votes']; ?></div>
                                            </div>
                                        <?php } else { ?>
                                            <div id="up<?= $comm['comment_id']; ?>" class="voters">
                                                <div data-id="<?= $comm['comment_id']; ?>" data-type="comment" class="up-id"></div>
                                                <div class="score"><?= $comm['comment_votes']; ?></div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
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
                            <a class="link" href="/comments/<?= $data['pNum'] - 1; ?>"> << <?= lang('Page'); ?> <?= $data['pNum'] - 1; ?></a> 
                        <?php } ?>
                        <?php if($data['pagesCount'] != $data['pNum'] && $data['pNum'] != 1) { ?>|<?php } ?> 
                        <?php if($data['pagesCount'] > $data['pNum']) { ?>
                            <a class="link" href="/comments/<?= $data['pNum'] + 1; ?>"><?= lang('Page'); ?>  <?= $data['pNum'] + 1; ?> >></a> 
                        <?php } ?>
                    </div>
                <?php } ?>
                    
                <?php } else { ?>
                    <div class="no-content"><?= lang('no-comment'); ?>...</div>
                <?php } ?>

            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('comments-desc'); ?>
            </div>
        </div>
        <?php if ($uid['id'] == 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
        <?php } ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>   