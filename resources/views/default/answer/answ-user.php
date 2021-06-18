<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1 class="top"><?= $data['h1']; ?></h1>

                <?php if (!empty($answers)) { ?>

                    <?php foreach ($answers as $answ) { ?> 
                    
                        <?php if($answ['answer_del'] == 0) { ?>
                            <div class="answ-telo_bottom">
                                <div class="answ-telo">
                                    <div class="answ-header date">
                                        <img class="ava" alt="<?= $answ['login']; ?>" src="<?= user_avatar_url($answ['avatar'], 'small'); ?>">
                                        <span class="user"> 
                                            <a href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a> 
                                            <?= $answ['date']; ?>
                                        </span> 
                                        <span class="otst"> | </span>
                                        <span>  
                                           + <?= $answ['answer_votes']; ?>
                                        </span>
                                        <span class="otst"> | </span>
                                        <span>
                                            <a href="/post/<?= $answ['post_id']; ?>/<?= $answ['post_slug']; ?>"><?= $answ['post_title']; ?></a>
                                        </span>  
                                    </div>
                                    <div class="answ-telo-body">
                                        <?= $answ['content']; ?> 
                                    </div>
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
        <?php if ($uid['id'] == 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
        <?php } else { ?>
            <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
        <?php } ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>