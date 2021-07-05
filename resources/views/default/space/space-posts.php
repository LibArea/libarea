<?php include TEMPLATE_DIR . '/header.php'; ?>

<?php if ($space_info['space_is_delete'] == 0) { ?>

    <?php if($space_info['space_cover_art'] != 'space_cover_no.jpeg') { ?>

        <style nonce="<?= $_SERVER['nonce']; ?>">
        .space-cover-box {background-image: url(/uploads/spaces/cover/<?= $space_info['space_cover_art']; ?>); background-position: 50% 50%;min-height: 300px;}
        </style>
        <div class="space-cover-box">
        <div class="wrap">
    <?php } else { ?> 

        <style nonce="<?= $_SERVER['nonce']; ?>">
        .space-box {background:<?= $space_info['space_color']; ?>;}
        </style>
        <div class="space-box" >
        <div class="wrap">
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
        <img alt="<?= $space_info['space_name']; ?>" class="space-box-img" src="<?= spase_logo_url($space_info['space_img'], 'max'); ?>">
        <div class="fons">
            <a title="<?= $space_info['space_name']; ?>" href="/s/<?= $space_info['space_slug']; ?>">
                <h1><?= $space_info['space_name']; ?></h1>
            </a>  
            <div class="space-slug">
                s/<?= $space_info['space_slug']; ?>
            </div> 
        </div>    
    </div>
    </div>
</div>
   
<div class="wrap">   
    <main>
        <ul class="nav-tabs">
            <?php if($data['sheet'] == 'feed') { ?>
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
            
                <div class="post-telo white-box">
                    <div class="post-header small">
                        <img class="ava" alt="<?= $post['login']; ?>" src="<?= user_avatar_url($post['avatar'], 'small'); ?>">
                        <span class="indent"></span> 
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>">
                                <?= $post['login']; ?>
                            </a> 
                        </span>
                        <span class="indent"></span> 
                          <span class="date"> 
                           <?= $post['post_date'] ?>
                        </span>
                    </div>
                    
                    <?php if($post['post_thumb_img']) { ?>
                        <a title="<?= $post['post_title']; ?>" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">                    
                            <img class="thumb no-mob" alt="<?= $post['post_title']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
                        </a>
                    <?php } ?>
                    
                    <div class="post-body">
                        <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                            <h2 class="title"><?= $post['post_title']; ?>
                                <?php if ($post['post_is_delete'] == 1) { ?> 
                                    <i class="icon trash"></i>
                                <?php } ?>
                                <?php if($post['post_closed'] == 1) { ?> 
                                    <i class="icon lock"></i>
                                <?php } ?>
                                <?php if($post['post_top'] == 1) { ?> 
                                    <i class="icon pin red"></i>
                                <?php } ?>
                                <?php if($post['post_lo'] > 0) { ?> 
                                    <i class="icon trophy lo"></i>
                                <?php } ?>
                                <?php if($post['post_type'] == 1) { ?> 
                                    <i class="icon question green"></i>
                                <?php } ?>
                                <?php if($post['post_translation'] == 1) { ?> 
                                    <span class="translation small lowercase"><?= lang('Translation'); ?></span>
                                <?php } ?>
                                <?php if($post['post_tl'] > 0) { ?> 
                                    <span class="trust-level small">tl<?= $post['post_tl']; ?></span>
                                <?php } ?>
                                <?php if($post['post_merged_id'] > 0) { ?> 
                                    <i class="icon graph red"></i>
                                <?php } ?>
                            </h2>
                        </a>

                        <?php if($post['post_url_domain']) { ?> 
                            <a class="date small indent-bid" href="/domain/<?= $post['post_url_domain']; ?>">
                                <i class="icon link"></i> <?= $post['post_url_domain']; ?>
                            </a> 
                        <?php } ?>
                        
                        <?= html_topic($post['topic_list'], 'date small indent-bid'); ?>
                        
                        <div class="post-details">
                            <div class="show_add_<?= $post['post_id']; ?>">
                                <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
                                    <?= $post['post_content_preview']; ?>
                                    <span class="s_<?= $post['post_id']; ?> show_detail"></span>
                                </div>
                            </div>
                        </div>

                        <?php if($post['post_content_img']) { ?> 
                                <div class="post-img">
                                    <a title="<?= $post['post_title']; ?>" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                        <img class="img-post" alt="<?= $post['post_title']; ?>" src="/uploads/posts/<?= $post['post_content_img']; ?>">
                                    </a>
                                </div>    
                        <?php } ?>

                        <div class="post-footer lowercase">
                            <?php if (!$uid['id']) { ?> 
                                <div id="vot<?= $post['post_id']; ?>" class="voters">
                                    <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                                    <div class="score"><?= $post['post_votes']; ?></div>
                                </div>
                            <?php } else { ?> 
                                <?php if ($post['votes_post_user_id'] || $uid['id'] == $post['post_user_id']) { ?>
                                    <div class="voters active">
                                        <div class="up-id"></div>
                                        <div class="score"><?= $post['post_votes']; ?></div>
                                    </div>
                                <?php } else { ?>
                                    <div id="up<?= $post['post_id']; ?>" class="voters">
                                        <div data-id="<?= $post['post_id']; ?>" data-type="post" class="up-id"></div>
                                        <div class="score"><?= $post['post_votes']; ?></div>
                                    </div>
                                <?php } ?> 
                            <?php } ?> 
                    
                            <?php if($post['post_answers_num'] !=0) { ?> 
                                <a class="right" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                    <?php if($post['post_type'] ==0) { ?>
                                       <i class="icon bubbles"></i> 
                                       <?= $post['post_answers_num'] + $post['post_comments_num']; ?> 
                                    <?php } else { ?>      
                                       <i class="icon bubbles"></i> 
                                       <?= $post['post_answers_num']; ?>  <?= $post['lang_num_answers']; ?>   
                                    <?php } ?>
                                </a>
                            <?php } ?> 
                        </div>

                    </div>                        
                </div>
            <?php } ?>
            
        <?php } else { ?>
            <div class="no-content"><?= lang('no-post'); ?>...</div>
        <?php } ?>  
        
        <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/s/' . $space_info['space_slug']); ?>
        
    </main>

    <aside> 
        <div class="info-space white-box">
            <div class="inner-padding">
                <div class="sb-space-short">
                        <?= $space_info['space_short_text']; ?>
                </div>
                
                <div class="sb-space-stat">
                    <div class="_bl">
                        <p class="bl-n"><a href="/u/<?= $space_info['login']; ?>"><?= $space_info['login']; ?></a></p>
                        <p class="bl-t"><?= lang('Created by'); ?></p>
                    </div>
                    <div class="_bl">
                        <?php if($space_info['space_id'] != 1) { ?>
                            <p class="bl-n"><?= $space_info['users']; ?></p>
                        <?php } else { ?>
                            <p class="bl-n">***</p>
                        <?php } ?>
                        <p class="bl-t"><?= lang('Reads'); ?></p>
                    </div>
                </div>
                
                <hr>
                
                <div class="sb-created">
                    <i class="icon calendar"></i> <?= $space_info['space_date']; ?>
                </div>
                
                <?php if(!$uid['id']) { ?> 
                    <div class="sb-add-space-post center">
                        <a class="add-space-post" href="/login"> 
                            <i class="icon pencil"></i>    
                            <?= lang('Create Post'); ?>
                        </a>
                    </div>    
                <?php } else { ?>
                    <div class="sb-add-space-post center">
                        <?php if($space_info['space_user_id'] == $uid['id']) { ?>
                            <a class="add-space-post" href="/post/add/space/<?= $space_info['space_id']; ?>"> 
                                <?= lang('Create Post'); ?>
                            </a>     
                        <?php } else { ?>
                            <?php if($space_signed == 1) { ?>
                                <?php if($space_info['space_permit_users'] == 1) { ?>
                                    <?php if($uid['trust_level'] == 5 || $space_info['space_user_id'] == $uid['id']) { ?>
                                        <a class="add-space-post" href="/post/add/space/<?= $space_info['space_id']; ?>"> 
                                            <?= lang('Create Post'); ?>
                                        </a>
                                    <?php } else { ?> 
                                        <span class="restricted"><?= lang('The owner restricted the publication'); ?></span>
                                    <?php } ?>                
                                <?php } else { ?>
                                    <a class="add-space-post" href="/post/add/space/<?= $space_info['space_id']; ?>"> 
                                        <?= lang('Create Post'); ?>
                                    </a>
                                <?php } ?>                            
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
                </div>
            </div>
            
            <div class="space-text-sb white-box">
                <div class="inner-padding">
                    <?= $space_info['space_text']; ?>
                </div>
            </div>

    </aside> 
</div>
<?php } else { ?>
    <main class="w-100">
        <center>
            <br>
            <i class="icon shield red ban-space"></i>
            <div class="no-content red"><?= lang('ban-space'); ?>...</div>
        </center>
    </main>
<?php } ?> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>