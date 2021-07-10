<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <div id="stHeader">
        <a href="/"><i class="icon home"></i></a> <span class="slash">\</span> <?= $post['post_title']; ?>
    </div>
 
    <main>
        <article class="post-full">

            <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
       
                <div class="white-box telo-detail_post<?php if($post['post_is_delete'] == 1) { ?> dell<?php } ?>">

                    <div class="post-body">
                        <h1 class="title">
                            <?= $post['post_title']; ?> 
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
                                <?php if($post['post_tl'] > 0) { ?> 
                                    <span class="trust-level small">tl<?= $post['post_tl']; ?></span>
                                <?php } ?>
                                <?php if($post['post_type'] == 1) { ?> 
                                    <i class="icon question green"></i>
                                <?php } ?>
                                <?php if($post['post_url_domain']) { ?> 
                                    <a class="date small" href="/domain/<?= $post['post_url_domain']; ?>">
                                        <?= $post['post_url_domain']; ?>
                                    </a> 
                                <?php } ?>
                        </h1>
                        <div class="post-footer-full small lowercase">
                            <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
                            <span class="indent"></span> 
                            <span class="user"> 
                                <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                            </span>
                            <span class="date"> 
                                <?= $post['post_date_lang']; ?>
                                <?php if($post['edit_date']) { ?> 
                                    (<?= lang('ed'); ?>) 
                                <?php } ?>
                            </span>
                            <?php if ($uid['id']) { ?>
                                <?php if($uid['login'] == $post['login']  || $uid['trust_level'] == 5) { ?>
                                    <span class="date">
                                        <a href="/post/edit/<?= $post['post_id']; ?>">
                                            <i class="icon pencil"></i>  
                                        </a>
                                    </span>
                                <?php } ?>     
                                <?php if($uid['login'] == $post['login']) { ?>    
                                    <?php if($post['post_draft'] == 0) { ?>
                                        <?php if($post['my_post'] == $post['post_id']) { ?>
                                                <span class="mu_post">+ <?= lang('in-the-profile'); ?></span>
                                                <span class="indent"> &#183; </span>
                                        <?php } else { ?> 
                                            <a class="user-mypost" data-opt="1" data-post="<?= $post['post_id']; ?>">
                                                <span class="mu_post"><?= lang('in-the-profile'); ?></span>
                                                <span class="indent"> &#183;  </span>
                                            </a>
                                        <?php } ?> 
                                    <?php } ?>    
                                <?php } ?> 
                                
                                <?php if ($post['favorite_post']){ ?>
                                   <span class="indent"></span>    
                                   <span class="user-post-fav" data-post="<?= $post['post_id']; ?>">
                                        <span class="my_favorite"><?= lang('remove-favorites'); ?></span>
                                   </span>   
                                <?php } else { ?>
                                    <span class="indent"></span>
                                    <span class="user-post-fav" data-post="<?= $post['post_id']; ?>">
                                        <span class="my_favorite"><?= lang('add-favorites'); ?></span>
                                    </span>
                                <?php } ?> 
                                
                                <?php if($uid['trust_level'] ==5) { ?>
                                    <span class="indent"> &#183; </span>
                                    <span id="cm_dell" class="cm_add_link">
                                        <a data-post="<?= $post['post_id']; ?>" class="del-post">
                                            <?php if($post['post_is_delete'] == 1) { ?>
                                                <?= lang('Recover'); ?>
                                            <?php } else { ?>
                                                <?= lang('Remove'); ?>
                                            <?php } ?>
                                        </a>
                                    </span>
                                    <small> - (<?= $post['post_hits_count']; ?>)</small>
                                <?php } ?>
                                
                            <?php } ?>
                        </div>  
                    </div>
                    <div class="post-body full">
                        <div class="post">
                       
                            <?php if($post['post_thumb_img']) { ?> 
                                <img class="thumb" alt="<?= $post['post_title']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
                            <?php } ?>
                        
                            <?= $post['post_content']; ?> 
                        </div> 
                        <?php if($lo) { ?>
                            <div class="lo-post">
                                <h3 class="lo">ЛО</h3>
                                <span class="right">
                                    <a rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comment_<?= $lo['comment_id']; ?>">
                                        <i class="icon arrow-down"></i>
                                    </a>
                                </span>
                                <?= $lo['comment_content']; ?> 
                            </div>     
                        <?php } ?>    
                        <?php if($post['post_url_domain']) { ?> 
                            <span class="post_url_detal">
                                <?= lang('Website'); ?>: <a rel="nofollow noreferrer ugc" href="<?= $post['post_url']; ?>">
                                   <?= $post['post_url_domain']; ?>
                                </a>
                            </span> 
                        <?php } ?>
                        
                    <?php if(!empty($post_related)) { ?>
                        <div class="related"> 
                        <h3 class="style small"><?= lang('Related'); ?>:</h3>
                            <?php $num = 0; ?>
                            <?php foreach ($post_related as $related) { ?>
                                <div class="related-box-num">
                                    <?php $num++; ?>
                                    <span><?= $num; ?></span>
                                    <a href="/post/<?= $related['post_id']; ?>/<?= $related['post_slug']; ?>">
                                        <?= $related['post_title']; ?>
                                    </a>
                               </div> 
                            <?php } ?>
                        </div>
                    <?php } ?>
                     
                    <?php if(!empty($topics)) { ?>
                        <div class="related"> 
                        <h3 class="style small"><?= lang('Topics'); ?>:</h3>
                            <?php foreach ($topics as $topic) { ?>
                                <a class="tags" href="/topic/<?= $topic['topic_slug']; ?>">
                                    <?= $topic['topic_title']; ?>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    
                    </div>
                    <div class="post-full-footer">
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
                        
                        <span class="right">
                            <i class="icon bubbles"></i>
                            <?= $post['post_answers_num'] + $post['post_comments_num'] ?>
                        </span>
                        
                    </div>
                    <div>                    
                        <?php if($post['post_type'] == 0 && $post['post_draft'] == 0) { ?>
                            <?php if ($uid['id']) { ?>
                               <?php if($post['post_closed'] == 0) { ?>
                                    <form id="add_answ" class="new_answer" action="/answer/add" accept-charset="UTF-8" method="post">
                                    <?= csrf_field() ?>
                                    <div class="redactor">
                                        <textarea minlength="6" class="wmd-input h-150 w-95" rows="5" placeholder="<?= lang('write-something'); ?>..." name="answer" id="wmd-input"></textarea>
                                        <div class="boxline"> 
                                            <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                                            <input type="hidden" name="answer_id" id="answer_id" value="0">
                                            <input type="submit" class="button" name="answit" value="<?= lang('Reply'); ?>" class="button">
                                        </div>
                                    </div>    
                                    </form>
                                <?php } ?>
                            <?php } else { ?>
                                <textarea rows="5" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>" name="answer" id="answer"></textarea>
                                <div> 
                                    <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="button" disabled="disabled">
                                </div> 
                            <?php } ?>
                        <?php } ?>
                    </div>
                   
                </div>

            <?php } else { ?>
                <div class="telo-detail_post  dell">
                     <?= lang('post-delete'); ?>...
                </div>   
            <?php } ?>
            
            <?php if($post['post_draft'] == 0) { ?> 
                <?php if($post['post_type'] == 0) { ?> 
                    <?php include TEMPLATE_DIR . '/post/comment-view.php'; ?>
                    <?php if($post['post_closed'] == 1) { ?> 
                        <p class="no-content"> <i class="icon info"></i> <?= lang('post-closed'); ?>...</p>
                    <?php } ?>
                <?php } else { ?>
                    <?php include TEMPLATE_DIR . '/post/questions-view.php'; ?>
                    <?php if($post['post_closed'] == 1) { ?>
                        <p class="no-content"><i class="icon lock"></i> <?= lang('The question is closed'); ?>...</p>
                    <?php } ?>
                <?php } ?> 
            <?php } else { ?>   
                <p class="no-content red"><i class="icon info"></i> <?= lang('This is a draft'); ?>...</p>
            <?php } ?>   
        </article>
    </main> 
 
    <aside>
        <div class="space-info white-box">
            <div class="inner-padding"> 
                <div class="space-info-img">
                    <a title="<?= $post['space_name']; ?>" class="space-info-title" href="/s/<?= $post['space_slug']; ?>">
                        <?= spase_logo_img($post['space_img'], 'max', $post['space_slug'], 'img-space'); ?>
                        <?= $post['space_name']; ?>
                    </a> 
                </div>    
                <div class="space-info-desc small"><?= $post['space_short_text']; ?></div> 
            </div>
        </div>
        <?php if($post['post_content_img']) { ?>
          <div class="space-info white-box">
            <div class="inner-padding big"> 
              <?= post_cover_img($post['post_content_img'], $post['post_title'], 'img-post'); ?>
            </div>
          </div>
        <?php } ?>    
        <div class="space-info white-box">
            <div class="inner-padding"> 
                <h3 class="recommend small"><?= lang('To share'); ?></h3> 
                <div class="social center" data-url="<?= Lori\Config::get(Lori\Config::PARAM_URL) . '/post/' . $post['post_id'] . '/' . $post['post_slug']; ?>" data-title="<?= $post['post_title']; ?>">		
                    <a class="push facebook" data-id="fb"><i class="icon social-facebook"></i></a>
                    <a class="push vkontakte" data-id="vk"><i class="icon social-vkontakte"></i></a>
                    <a class="push twitter" data-id="tw"><i class="icon twitter"></i></a>
                    <a class="push pinterest" data-id="pin"><i class="icon social-pinterest"></i></a>
                </div>
            </div>
        </div>
 
        <?php if($recommend) { ?> 
            <div class="white-box sticky recommend">
                <div class="inner-padding">
                    <h3 class="recommend small"><?= lang('Recommended'); ?></h3>  
                    <?php $n=0; foreach ($recommend as  $rec_post) { $n++; ?>
                         <div class="l-rec-small"> 
                            <div class="l-rec">0<?= $n; ?></div> 
                            <div class="l-rec-telo"> 
                                <a class="edit-bl"  href="/post/<?= $rec_post['post_id']; ?>/<?= $rec_post['post_slug']; ?>">
                                    <?= $rec_post['post_title']; ?>  
                                </a>
                                <?php if($rec_post['post_answers_num'] !=0) { ?>
                                    <span class="n-comm">+<?= $rec_post['post_answers_num'] ?></span>
                                <?php } ?> 
                            </div>
                       </div>
                    <?php } ?> 
                </div>    
            </div> 
        <?php } ?> 
    </aside>
</div>    

<script nonce="<?= $_SERVER['nonce']; ?>">
$(document).ready(function() {
  $('.img-post').magnificPopup({
    items: {
      src: '/uploads/posts/cover/<?= $post['post_content_img']; ?>'
    },
    type: 'image'
  });
});
</script>  
<?php include TEMPLATE_DIR . '/footer.php'; ?> 