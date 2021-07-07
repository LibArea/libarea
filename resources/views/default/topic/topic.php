<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                
            <div class="box-flex">
                <div>
                    <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'ava-94'); ?>
                </div>
                <div class="indent-bid box-100">   
                    <h1 class="topics">
                        <?= $data['h1']; ?>
                        <?php if($uid['trust_level'] == 5) { ?>
                            <a class="right" href="/admin/topic/<?= $topic['topic_id']; ?>/edit"> 
                                <i class="icon pencil"></i>          
                            </a>
                        <?php } ?>
                    </h1>
                    <div class="small"><?= $topic['topic_description']; ?></div>
                    <div class="topics-footer">
                        <?php if(!$uid['id']) { ?> 
                             <a href="/login"><div class="add-focus focus-topic">+ <?= lang('Read'); ?></div></a>
                        <?php } else { ?>
                            <?php if($topic_signed == 1) { ?>
                                <div data-id="<?= $topic['topic_id']; ?>" class="del-focus focus-topic">
                                    <?= lang('Unsubscribe'); ?>
                                </div>
                            <?php } else { ?> 
                                <div data-id="<?= $topic['topic_id']; ?>" class="add-focus focus-topic">
                                    + <?= lang('Read'); ?>
                                </div>
                            <?php } ?>   
                        <?php } ?> 
                        <a title="<?= lang('Info'); ?>" class="small lowercase right gray" href="/topic/<?= $topic['topic_slug']; ?>/info">
                            <i class="icon book-open"></i>
                        </a>
                    </div>    
                </div>
                
            </div>  
                                
            </div>
        </div>             
        <div class="white-box">
            <div class="inner-padding">          
                 <?php if (!empty($posts)) { ?> 
    
                    <?php foreach ($posts as  $post) { ?>
                       <div class="post-telo white-box">
                          <div class="post-header small">
                            <?= user_avatar_img($post['avatar'], 'max', $post['login'], 'ava'); ?> 
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
                              <?php if (!$uid['id']) { ?> 
                                <div id="vot<?= $post['post_id']; ?>" class="voters">
                                  <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                                  <div class="score">
                                    <?= $post['post_votes'] ? '+'.$post['post_votes'] : $post['post_votes']; ?>
                                  </div>
                                </div>
                              <?php } else { ?> 
                                <?php if ($post['votes_post_user_id'] || $uid['id'] == $post['post_user_id']) { ?>
                                  <div class="voters active">
                                    <div class="up-id"></div>
                                    <div class="score">
                                      <?= $post['post_votes'] ? '+'.$post['post_votes'] : $post['post_votes']; ?>
                                    </div>
                                  </div>
                                <?php } else { ?>
                                  <div id="up<?= $post['post_id']; ?>" class="voters">
                                    <div data-id="<?= $post['post_id']; ?>" data-type="post" class="up-id"></div>
                                    <div class="score">
                                      <?= $post['post_votes'] ? '+'.$post['post_votes'] : $post['post_votes']; ?>
                                    </div>
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
                
                <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], 'topic' . $topic['topic_slug']); ?>
                
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <div class="box-flex">
                    <div class="box-post center box-number">
                        <div class="style small gray lowercase"><?= lang('Posts-m'); ?></div>
                        <?= $topic['topic_count']; ?>
                    </div>
                    <div class="box-fav center box-number">
                        <div class="style small gray lowercase"><?= lang('Reads'); ?></div>
                        <?= $topic['topic_focus_count']; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(!empty($main_topic)) { ?>
            <div class="white-box">
                <div class="inner-padding big"> 
                    <h3 class="style small"><?= lang('Root'); ?></h3>
                    <div class="related-box">
                        <a class="tags" href="/topic/<?= $main_topic['topic_slug']; ?>">
                            <?= $main_topic['topic_title']; ?>
                        </a>
                   </div> 
                </div>
            </div>
        <?php } ?> 
         
        <?php if(!empty($subtopics)) { ?>
            <div class="white-box">
                <div class="inner-padding big"> 
                    <h3 class="style small"><?= lang('Subtopics'); ?></h3>
                    <?php foreach ($subtopics as $sub) { ?>
                        <div class="related-box">
                            <a class="tags" href="/topic/<?= $sub['topic_slug']; ?>">
                                <?= $sub['topic_title']; ?>
                            </a>
                       </div> 
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

       
        <?php if(!empty($topic_related)) { ?>
            <div class="white-box">
                <div class="inner-padding big"> 
                    <h3 class="style small"><?= lang('Related'); ?></h3>
                    <?php foreach ($topic_related as $related) { ?>
                        <div class="related-box">
                            <a class="tags" href="/topic/<?= $related['topic_slug']; ?>">
                                <?= $related['topic_title']; ?>
                            </a>
                       </div> 
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
           
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>        