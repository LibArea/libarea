<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <?php if (!empty($answers)) { ?>

                    <?php foreach ($answers as $answ) { ?> 
                        <?php if($answ['answer_del'] == 0) { ?>
                            <div class="answ-telo_bottom">
                                <div class="small">
                                    <img class="ava" alt="<?= $answ['login']; ?>" src="<?= user_avatar_url($answ['avatar'], 'small'); ?>">
                                    
                                    <a class="date" href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a>
                                    
                                    <span class="date"><?= $answ['date']; ?></span>
                                   
                                    <span class="indent"> &#183; </span>
                                    <a href="/post/<?= $answ['post_id']; ?>/<?= $answ['post_slug']; ?>"><?= $answ['post_title']; ?></a>
                                </div>
                                <div class="telo-body">
                                    <?= $answ['content']; ?> 
                                </div>
                                <div class="post-full-footer date">
                                    <div class="answer-up-id"></div> + <?= $answ['answer_votes']; ?>
                                </div>
                            </div>  
                        <?php } else { ?>    
                            <div class="dell answ-telo_bottom"> 
                                <div class="voters"></div>
                                ~ <?= lang('answer-deleted'); ?>
                            </div>
                        <?php } ?>     
                                
                    <?php } ?>

                <?php } else { ?>
                    <div class="no-content"><i class="icon info"></i> <?= lang('no-answers'); ?>...</div>
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>