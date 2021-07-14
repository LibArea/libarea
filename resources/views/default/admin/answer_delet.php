<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                </h1>

                <?php if (!empty($answers)) { ?>
              
                    <?php foreach ($answers as $answer) { ?>  
                    
                        <div class="answ-telo_bottom" id="answer_<?= $answer['comment_id']; ?>">
                            <div class="small">
                                <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'ava'); ?>
                                <a class="date" href="/u/<?= $answer['login']; ?>"><?= $answer['login']; ?></a> 
                                
                                <span class="date"><?= $answer['date']; ?></span>

                                <span class="indent"> &#183; </span>
                                <a href="/post/<?= $answer['post_id']; ?>/<?= $answer['post_slug']; ?>">
                                    <?= $answer['post_title']; ?>
                                </a>
                            </div>
                            <div class="answ-telo-body">
                                <?= $answer['content']; ?> 
                            </div>
                           <div class="post-full-footer date">
                               + <?= $answer['answer_votes']; ?>
                               <span id="cm_dell" class="right comment_link small">
                                    <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action">
                                        <?= lang('Recover'); ?>
                                    </a>
                               </span>
                           </div>
                        </div>
                    <?php } ?>
                    
                <?php } else { ?>
                    <div class="no-content"> <i class="icon info"></i> <?= lang('No answers'); ?>...</div>
                <?php } ?>

            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/answers'); ?>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>  