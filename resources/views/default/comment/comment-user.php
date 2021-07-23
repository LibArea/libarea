<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>
            </div>
        </div>
        <?php if (!empty($comments)) { ?>
            <?php foreach ($comments as $comm) { ?> 
                <?php if($comm['comment_is_deleted'] == 0) { ?>
                    <div class="white-box">
                        <div class="post-header small">
                            <a class="gray" href="/u/<?= $comm['login']; ?>">
                                <?= user_avatar_img($comm['avatar'], 'max', $comm['login'], 'ava'); ?> 
                                <span class="indent"></span>
                                <?= $comm['login']; ?>
                            </a> 
                            <span class="gray">
                                <?= $comm['date']; ?>
                            </span>
                            <a class="indent" href="/post/<?= $comm['post_id']; ?>/<?= $comm['post_slug']; ?>"><?= $comm['post_title']; ?></a>
                        </div>
                        <span class="indent"></span>
                        <div class="post-details">
                            <?= $comm['comment_content']; ?> 
                        </div>
                        <div class="post-footer gray">
                            + <?= $comm['comment_votes']; ?>
                        </div>
                    </div>
                <?php } else { ?>    
                    <div class="dell comm-telo_bottom"> 
                        <div class="voters"></div>
                        ~ <?= lang('Comment deleted'); ?>
                    </div>
                <?php } ?>     
                        
            <?php } ?>

        <?php } else { ?>
            <p class="no-content gray">
                <i class="light-icon-info-square middle"></i> 
                <span class="middle"><?= lang('There are no comments'); ?>...</span>
            </p>
        <?php } ?>
 
    </main>
    <aside>
        <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>