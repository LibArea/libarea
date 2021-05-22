<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="telo">
    <ul class="nav-tabs">
        <?php if($type == 'feed') { ?>
            <li class="active">
                <span><?= lang('Feed'); ?></span>
            </li>
            <li>
                <a href="/top">
                    <span>Top</span>
                </a>
            </li>
        <?php } else { ?>
            <li>  
                <a href="/">
                    <span><?= lang('Feed'); ?></span>
                </a>
            </li>    
            <li class="active">
                <span>Top</span>
            </li>
        <?php } ?>        
    </ul>

    <?php if (!empty($posts)) { ?> 
    
        <?php foreach ($posts as  $post) { ?>
        
            <div class="post-telo">
                <?php if (!$uid['id']) { ?> 
                    <div id="vot<?= $post['post_id']; ?>" class="voters">
                        <a rel="nofollow" href="/login"><div class="post-up-id"></div></a>
                        <div class="score"><?= $post['post_votes']; ?></div>
                    </div>
                <?php } else { ?> 
                    <?php if ($post['votes_post_user_id'] || $uid['id'] == $post['post_user_id']) { ?>
                        <div class="voters active">
                            <div class="post-up-id"></div>
                            <div class="score"><?= $post['post_votes']; ?></div>
                        </div>
                    <?php } else { ?>
                        <div id="up<?= $post['post_id']; ?>" class="voters">
                            <div data-id="<?= $post['post_id']; ?>" class="post-up-id"></div>
                            <div class="score"><?= $post['post_votes']; ?></div>
                        </div>
                    <?php } ?> 
                <?php } ?> 
                
                <div class="post-body">
                    <div class="footer">
                        <img class="ava" alt="<?= $post['login']; ?>" src="/uploads/users/avatars/small/<?= $post['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>">
                                <?= $post['login']; ?>
                            </a> 
                        </span>
                        <span class="date"> 
                           <?= $post['post_date'] ?>
                        </span>
                        <?php if($post['post_answers_num'] !=0) { ?> 
                            <span class="otst"> | </span>
                            <a class="u-url" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                <?php if($post['post_type'] ==0) { ?>
                                    <?= $post['post_answers_num'] + $post['post_comments_num']; ?> коммент...
                                <?php } else { ?>      
                                    <?= $post['post_answers_num']; ?>  <?= $post['lang_num_answers']; ?>   
                                <?php } ?>
                            </a>
                        <?php } ?>
                    </div>
                    <a class="u-url" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                        <h2 class="title"><?= $post['post_title']; ?></h2>
                        <?php if ($post['post_is_delete'] == 1) { ?> 
                            <i class="icon trash"></i>
                        <?php } ?>
                        <?php if($post['post_closed'] == 1) { ?> 
                            <i class="icon lock"></i>
                        <?php } ?>
                        <?php if($post['post_top'] == 1) { ?> 
                            <i class="icon pin"></i>
                        <?php } ?>
                        <?php if($post['post_lo'] > 0) { ?> 
                            <i class="icon trophy lo"></i>
                        <?php } ?>
                        <?php if($post['post_type'] == 1) { ?> 
                            <i class="icon question qa"></i>
                        <?php } ?>
                    </a>
                    <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                        <?= $post['space_name']; ?>
                    </a>
                    
                    <?php if($post['post_url_domain']) { ?> 
                        <a class="post_url" href="/domain/<?= $post['post_url_domain']; ?>">
                            <?= $post['post_url_domain']; ?>
                        </a> 
                    <?php } ?>
                    
                    <div class="post-details">
                        <?php if($post['post_thumb_img']) { ?> 
                            <img class="thumb" alt="<?= $post['post_url']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
                        <?php } ?>
                    
                        <div class="show_add_<?= $post['post_id']; ?>">
                            <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
                                <?= $post['post_content_preview']; ?>
                                <span class="s_<?= $post['post_id']; ?> show_detail">...</span>
                            </div>
                        </div>
                        
                         <?php if($post['post_content_img']) { ?> 
                            <div class="img-post-bl">
                                <img class="img-post" alt="<?= $post['post_title']; ?>" src="/uploads/posts/<?= $post['post_content_img']; ?>">
                            </div>    
                        <?php } ?>
                    </div>
                </div>                        
            </div>
        <?php } ?>
        
    <?php } else { ?>
        <div class="no-content"><?= lang('no-post'); ?>...</div>
    <?php } ?>
    
   <?php if(!($data['pNum'] > $data['pagesCount'])) { ?>
        <div class="pagination">   
            <?php if($data['pNum'] != 1) { ?> 
                <a href="/<?= $data['pNum'] - 1; ?>"> << <?= lang('Page'); ?> <?= $data['pNum'] - 1; ?></a> 
            <?php } ?>
            <?php if($data['pagesCount'] != $data['pNum'] && $data['pNum'] != 1) { ?>|<?php } ?> 
            <?php if($data['pagesCount'] > $data['pNum']) { ?>
                <a href="/<?= $data['pNum'] + 1; ?>"><?= lang('Page'); ?>  <?= $data['pNum'] + 1; ?> >></a> 
            <?php } ?>
        </div>
    <?php } ?>
 
</main>

<?php include TEMPLATE_DIR . '/footer.php'; ?>