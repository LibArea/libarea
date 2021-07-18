<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <?php if($link['link_title']) { ?>
                    <div class="right heart-link">
                        <?= votes($uid['id'], $link, 'link'); ?> 
                    </div>
                    <h1 class="domain"><?= $link['link_title']; ?>
                        <?php if($uid['trust_level'] > 4) { ?>
                            <span class="indent"></span>
                            <a class="small" title="<?= lang('Edit'); ?>" href="/web/edit/<?= $link['link_id']; ?>">
                                <i class="icon pencil"></i>
                            </a>
                        <?php } ?>
                    </h1>
                    <div class="gray">
                    <?= $link['link_content']; ?>
                    </div>
                    <div class="domain-footer-small">
                        <a class="green" rel="nofollow noreferrer ugc" href="<?= $link['link_url']; ?>">
                            <?= favicon_img($link['link_id'], $link['link_url_domain']); ?>
                            <?= $link['link_url']; ?>
                        </a> 
                        
                        <span class="right"><?= $link['link_count']; ?></span>
                    </div>
                <?php } else { ?>
                    <h1><?= $data['h1']; ?></h1>
                <?php } ?>
            </div>
        </div>
        <?php if (!empty($posts)) { ?> 
        
            <?php foreach ($posts as  $post) { ?>
            
                <div class="post-telo white-box">
                    <div class="post-header small">
                        <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
                        <span class="indent"></span> 
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>">
                                <?= $post['login']; ?>
                            </a> 
                        </span>
                        <span class="indent"></span> 
                        <a href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                            <?= $post['space_name']; ?>
                        </a> 
                        
                        <span class="indent"></span> 
                          <span class="date"> 
                           <?= $post['post_date'] ?>
                        </span>
                    </div>
                    <?php if($post['post_thumb_img']) { ?> 
                        <img class="thumb" alt="<?= $post['post_title']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
                    <?php } ?>
                    <div class="post-body">
                        <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                            <h2 class="title"><?= $post['post_title']; ?>
                                <?php if ($post['post_is_deleted'] == 1) { ?> 
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
                            <a class="indent small indent-bid" href="/domain/<?= $post['post_url_domain']; ?>">
                                <i class="icon link"></i> <?= $post['post_url_domain']; ?>
                            </a> 
                        <?php } ?>
                        
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
                                        <?= post_cover_img($post['post_content_img'], $post['post_title'], 'img-post'); ?>
                                    </a>
                                </div>    
                        <?php } ?>

                        <div class="post-footer lowercase">
                            <?= votes($uid['id'], $post, 'post'); ?>
                    
                            <?php if($post['post_answers_count'] !=0) { ?> 
                                <a class="right" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                    <?php if($post['post_type'] ==0) { ?>
                                       <i class="icon bubbles"></i> 
                                       <?= $post['post_answers_count'] + $post['post_comments_count']; ?> 
                                    <?php } else { ?>      
                                       <i class="icon bubbles"></i> 
                                       <?= $post['post_answers_count']; ?>  <?= $post['lang_num_answers']; ?>   
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
                        <a class="date small" href="/domain/<?= $domain['link_url_domain']; ?>">
                            <i class="icon link"></i> <?= $domain['link_url_domain']; ?> 
                            <sup class="date small"><?= $domain['link_count']; ?></sup>
                        </a><br>
                    <?php } ?>
                <?php } else { ?>
                    <p><?= lang('There are no domains'); ?>...</p>
                <?php } ?>
            </div>                        
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 