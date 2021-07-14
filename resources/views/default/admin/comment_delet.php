<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                </h1>

                <?php if (!empty($comments)) { ?>
              
                    <?php foreach ($comments as $comment) { ?>  
                    
                        <div class="comm-telo_bottom" id="comment_<?= $comment['comment_id']; ?>">
                            <div class="small">
                                <?= user_avatar_img($comment['avatar'], 'small', $comment['login'], 'ava'); ?>
                                <a class="date" href="/u/<?= $comment['login']; ?>"><?= $comment['login']; ?></a> 
                                
                                <span class="date"><?= $comment['date']; ?></span>

                                <span class="indent"> &#183; </span>
                                <a href="/post/<?= $comment['post_id']; ?>/<?= $comment['post_slug']; ?>">
                                    <?= $comment['post_title']; ?>
                                </a>
                            </div>
                            <div class="comm-telo-body">
                                <?= $comment['content']; ?> 
                            </div>
                           <div class="post-full-footer date">
                               + <?= $comment['comment_votes']; ?>
                               <span id="cm_dell" class="right comment_link small">
                                    <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action">
                                        <?= lang('Recover'); ?>
                                    </a>
                               </span>
                           </div>
                        </div>
                    <?php } ?>
                    
                <?php } else { ?>
                    <div class="no-content"><?= lang('no-comment'); ?>...</div>
                <?php } ?>
            </div>
        </div>
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/admin/comments'); ?>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>  