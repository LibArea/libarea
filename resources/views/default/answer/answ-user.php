<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <h1 class="top"><?= $data['h1']; ?></h1>

    <?php if (!empty($answers)) { ?>

        <?php foreach ($answers as $answ) { ?> 
        
            <?php if($answ['answer_del'] == 0) { ?>
                <div class="answ-telo_bottom">
                    <div class="voters">
                        <div class="answ-up-id"></div>
                        <div class="score"><?= $answ['answer_votes']; ?></div>
                    </div>

                    <div class="answ-telo">
                        <div class="answ-header">
                            <img class="ava" alt="<?= $answ['login']; ?>" src="/uploads/users/avatars/small/<?= $answ['avatar']; ?>">
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
                            <?= $answ['content']; ?> 
                        </div>
                    </div>
                </div>  
            <?php } else { ?>    
                <div class="dell answ-telo_bottom"> 
                    <div class="voters"></div>
                    ~ Ответ удален
                </div>
            <?php } ?>     
                    
        <?php } ?>

    <?php } else { ?>
        <div class="no-content"><?= lang('no-answers'); ?>...</div>
    <?php } ?>
</main> 
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>