<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <h1><?= $data['h1']; ?></h1>
            </div>
        </div>
        <?php if (!empty($posts)) { ?> 
        
            <?php foreach ($posts as  $post) { ?>
            
                <div class="post-telo white-box">
                    <div class="post-header small">
                    
                        <img class="ava" alt="<?= $post['login']; ?>" src="<?= user_avatar_url($post['avatar'], 'small'); ?>">
                        <span class="otst"></span> 
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>">
                                <?= $post['login']; ?>
                            </a> 
                        </span>
                        <span class="otst"></span> 
                        <a href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                            <?= $post['space_name']; ?>
                        </a> 
                        
                        <span class="otst"></span> 
                          <span class="date"> 
                           <?= $post['post_date'] ?>
                        </span>
                    </div>
                    
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
                                <?php if($post['post_url_domain']) { ?> 
                                    <a class="post_url" href="/domain/<?= $post['post_url_domain']; ?>">
                                        <?= $post['post_url_domain']; ?>
                                    </a> 
                                <?php } ?>
                            </h2>
                        </a>

 
                        
                        <div class="post-details">
                            <?php if($post['post_thumb_img']) { ?> 
                                <img class="thumb" alt="<?= $post['post_title']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
                            <?php } ?>
                        
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
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <?php if (!empty($domains)) { ?>
                    <div class="bar-title small"><?= lang('Domains'); ?></div>
                    <?php foreach ($domains as  $domain) { ?>
                        <a class="space-u tag-u" href="/domain/<?= $domain['post_url_domain']; ?>">
                            <i class="icon link"></i> <?= $domain['post_url_domain']; ?>
                        </a><br>
                    <?php } ?>
                <?php } ?>
            </div>                        
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 