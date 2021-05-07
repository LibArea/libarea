<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/_block/left-menu.php'; ?>
<main>
    <h1 class="top"><?php echo $data['h1']; ?></h1>

    <div class="telo answers">
        <?php if (!empty($answers)) { ?>
      
            <?php foreach ($answers as $answ) { ?>  
                <?php if($answ['answer_del'] == 0) { ?>
                    <div class="answ-telo_bottom">
                        
                        <?php if (!$uid['id']) { ?> 
                            <div class="voters">
                                <a rel="nofollow" href="/login"><div class="answ-up-id"></div></a>
                                <div class="score"><?= $answ['answer_votes']; ?></div>
                            </div>
                        <?php } else { ?>
                            <?php if ($answ['answ_vote_status'] || $uid['id'] == $answ['answer_user_id']) { ?>
                                <div class="voters active">
                                    <div class="answ-up-id"></div>
                                    <div class="score"><?= $answ['answer_votes']; ?></div>
                                </div>
                            <?php } else { ?>
                                <div id="up<?= $answ['answer_id']; ?>" class="voters">
                                    <div data-id="<?= $answ['answer_id']; ?>" class="answ-up-id"></div>
                                    <div class="score"><?= $answ['answer_votes']; ?></div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        
                        <div class="answ-telo">
                            <div class="answ-header">
                                <img class="ava" src="/uploads/avatar/small/<?= $answ['avatar'] ?>">
                                <span class="user"> 
                                    <a href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a> 
                                    
                                    <?= $answ['date']; ?>
                                </span> 
             
                                <span class="otst"> | </span>
                                <span class="date">  
                                   <a href="/post/<?= $answ['post_id']; ?>/<?= $answ['post_slug']; ?>"><?= $answ['post_title']; ?></a>
                                </span>
                            </div>
                           
                            <div class="answ-telo-body">
                                <?= $answ['answers_content']; ?> 
                            </div>
                        </div>
                    </div>
                <?php } else { ?>    
                    <div class="dell answ-telo_bottom"> 
                        <div class="voters"></div>
                        ~ <?= lang('Answer deleted'); ?>
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
<?php include TEMPLATE_DIR . '/footer.php'; ?>   