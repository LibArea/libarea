<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <?php if($space_info['space_cover_art'] != 'space_cover_no.jpeg') { ?>

        <style nonce="<?= $_SERVER['nonce']; ?>">
        .space-cover-box {background-image: url(/uploads/spaces/cover/<?= $space_info['space_cover_art']; ?>); background-position: 50% 50%;min-height: 300px;margin-top: -24px;}
        </style>
        <div class="space-cover-box">
        
    <?php } else { ?> 

        <style nonce="<?= $_SERVER['nonce']; ?>">
        .space-box {background:<?= $space_info['space_color']; ?>;}
        </style>
        <div class="space-box" >
        
    <?php } ?>   
    

    <?php if(!$uid['id']) { ?> 
            <div class="right"> 
                <a href="/login"><div class="hide-space-id yes-space">+ <?= lang('Read'); ?></div></a>
            </div>
    <?php } else { ?>
        <?php if($space_info['space_id'] !=1) { ?>
            <?php if($space_info['space_user_id'] != $uid['id']) { ?>
                <div class="right"> 
                    <?php if($space_signed == 1) { ?>
                        <div data-id="<?= $space_info['space_id']; ?>" class="hide-space-id no-space">
                            <?= lang('Unsubscribe'); ?>
                        </div>
                    <?php } else { ?> 
                        <div data-id="<?= $space_info['space_id']; ?>" class="hide-space-id yes-space">
                            + <?= lang('Read'); ?>
                        </div>
                    <?php } ?>   
                </div>
            <?php } ?>
        <?php } ?>                 
    <?php } ?> 
    <div class="space-text">
        <img class="space-box-img" src="/uploads/spaces/logos/<?= $space_info['space_img']; ?>">
        <div class="fons">
            <a title="<?= $space_info['space_name']; ?>" href="/s/<?= $space_info['space_slug']; ?>">
                <h1><?= $space_info['space_name']; ?></h1>
            </a>  
            <div class="data">
            <?= lang('Created by'); ?>: <a href="/u/<?= $space_info['login']; ?>"><?= $space_info['login']; ?></a>
            
            — <?= $space_info['space_date']; ?>
        
            </div> 
        </div>    
    </div>
   
    </div>
 
<?php if ($space_info['space_is_delete'] == 0) { ?>
 
    <ul class="nav-tabs">
        <?php if($type == 'feed') { ?>
            <li class="active">
               <span><?= lang('Feed'); ?></span>
            </li>
            <li>
                <a href="/s/<?= $space_info['space_slug']; ?>/top">
                    <span>Top</span>
                </a>
            </li>
        <?php } else { ?>
            <li>  
                <a href="/s/<?= $space_info['space_slug']; ?>">
                    <span><?= lang('Feed'); ?></span>
                </a>
            </li>    
            <li class="active">
                <span>Top</span>
            </li>
        <?php } ?> 
        <?php if($uid['trust_level'] == 5 || $space_info['space_user_id'] == $uid['id']) { ?>        
            <li class="right">
                <a class="edit-space" href="/space/<?= $space_info['space_slug']; ?>/edit">
                    <span><?= lang('Edit'); ?></span>
                </a>
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
                    
                    <?php if($post['post_url_domain']) { ?> 
                        <a class="post_url" href="/domain/<?= $post['post_url_domain']; ?>">
                            <?= $post['post_url_domain']; ?>
                        </a> 
                    <?php } ?>
                    
                    <div class="post-details">
                        <?php if($post['post_thumb_img']) { ?> 
                            <img class="thumb" alt="<?= $post['post_url']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
                        <?php } ?>
                    
                        <?= $post['post_content_preview']; ?>...
                        
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
</main>
 
<?php } else { ?>
    <main>
        <div class="no-content red"><?= lang('ban-space'); ?>...</div>
    </main>
<?php } ?> 
<aside> 
    <div class="info-space">
        <?php if($space_info['space_id'] != 1) { ?>
            <div class="space-users-count">
                Читают <?= $space_info['users']; ?>
            </div>
        <?php } ?>
        
        <div class="space-text-sb">
            <?= $space_info['space_text']; ?>
        </div>
      
        <?php if (!empty($tags)) { ?>
            <div class="space-tags">
                <div class="menu-m"><?= lang('Tags'); ?></div>
                <?php foreach ($tags as  $tag) { ?>  
                    <a <?php if ($uid['uri'] == '/s/'.$tag['space_slug'] .'/'.$tag['st_id']) { ?> class="avtive" <?php } ?> href="/s/<?= $space_info['space_slug']; ?>/<?= $tag['st_id']; ?>">
                        <?= $tag['st_title']; ?>
                    </a>
                <?php } ?>
            </div>
        <?php } ?> 
        <br>
    </div>
</aside> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>