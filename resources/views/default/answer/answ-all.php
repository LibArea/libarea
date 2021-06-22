<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <div class="telo">
                    <?php if (!empty($answers)) { ?>
                  
                        <?php foreach ($answers as $answ) { ?>  
                            <?php if($answ['answer_del'] == 0) { ?>
                                <div class="answ-telo_bottom">
                                    <div class="answ-header small">
                                        <img class="ava" src="<?= user_avatar_url($answ['avatar'], 'small'); ?>">
                                        
                                        <a class="date" href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a> 
                                        
                                        <span class="date"><?= $answ['date']; ?></span>
                                         
                                        <span class="indent"> &#183; </span>
                                        <a href="/post/<?= $answ['post_id']; ?>/<?= $answ['post_slug']; ?>"><?= $answ['post_title']; ?></a>
                                    </div>
                                   
                                    <div class="answ-telo-body">
                                        <?= $answ['answer_content']; ?> 
                                    </div>

                                    <div class="post-full-footer date">
                                        <?php if (!$uid['id']) { ?> 
                                            <div class="voters">
                                                <a rel="nofollow" href="/login"><div class="answer-up-id"></div></a>
                                                <div class="score"><?= $answ['answer_votes']; ?></div>
                                            </div>
                                        <?php } else { ?>
                                            <?php if ($answ['answ_vote_status'] || $uid['id'] == $answ['answer_user_id']) { ?>
                                                <div class="voters active">
                                                    <div class="answer-up-id"></div>
                                                    <div class="score"><?= $answ['answer_votes']; ?></div>
                                                </div>
                                            <?php } else { ?>
                                                <div id="up<?= $answ['answer_id']; ?>" class="voters">
                                                    <div data-id="<?= $answ['answer_id']; ?>" class="answer-up-id"></div>
                                                    <div class="score"><?= $answ['answer_votes']; ?></div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
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
        </div>        
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('answers-desc'); ?>
            </div>
        </div>
        <?php if ($uid['id'] == 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
        <?php } ?>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>   