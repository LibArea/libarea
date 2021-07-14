<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <?php if (!empty($comments)) { ?>
              
                    <?php foreach ($comments as $comment) { ?>  
                    
                        <?php if($comment['comment_is_deleted'] == 0) { ?>
                            <div class="comm-telo_bottom">
                                <div class="small">
                                    <?= user_avatar_img($comment['avatar'], 'small', $comment['login'], 'ava'); ?>
                                    <a class="date" href="/u/<?= $comment['login']; ?>"><?= $comment['login']; ?></a> 
                                     
                                    <span class="date"><?= $comment['date']; ?></span>
                                    
                                    <span class="indent"> &#183; </span>
                                    <a href="/post/<?= $comment['post_id']; ?>/<?= $comment['post_slug']; ?>#comment_<?= $comment['comment_id']; ?>">
                                        <?= $comment['post_title']; ?>
                                    </a>
                                </div>

                                <div class="comm-telo-body">
                                    <?= $comment['comment_content']; ?> 
                                </div>
                           
                                <div class="post-full-footer date">
                                 + <?= $comment['comment_votes']; ?>                            
                                </div>
                            </div>
                        <?php } else { ?>    
                            <div class="dell comm-telo_bottom"> 
                                <div class="voters"></div>
                                ~ <?= lang('Comment deleted'); ?>
                            </div>
                        <?php } ?> 
                    <?php } ?>
                    
                <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/comments'); ?>
                    
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