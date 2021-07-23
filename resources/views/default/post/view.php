<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <div id="stHeader">
        <a href="/"><i class="light-icon-home middle"></i></a> 
        <span class="separator middle">\</span>  
        <span class="middle"><?= $post['post_title']; ?></span>
    </div>
 
    <main>
        <article class="post-full">
            <?php if($post['post_is_deleted'] == 0 || $uid['trust_level'] == 5) { ?>
                <div class="white-box telo-detail-post<?php if($post['post_is_deleted'] == 1) { ?> dell<?php } ?>">

                    <div class="post-body">
                        <h1 class="title">
                            <?= $post['post_title']; ?> 
                            <?php if ($post['post_is_deleted'] == 1) { ?> 
                              <i class="light-icon-trash red"></i>
                            <?php } ?>
                            <?php if($post['post_closed'] == 1) { ?> 
                              <i class="light-icon-lock"></i>
                            <?php } ?>
                            <?php if($post['post_top'] == 1) { ?> 
                              <i class="light-icon-arrow-narrow-up red"></i>
                            <?php } ?>
                            <?php if($post['post_lo'] > 0) { ?> 
                              <i class="light-icon-checks red"></i>
                            <?php } ?>
                            <?php if($post['post_type'] == 1) { ?> 
                              <i class="light-icon-language green"></i>
                            <?php } ?>
                            <?php if($post['post_translation'] == 1) { ?> 
                              <span class="translation small lowercase"><?= lang('Translation'); ?></span>
                            <?php } ?>
                            <?php if($post['post_tl'] > 0) { ?> 
                              <span class="trust-level small">tl<?= $post['post_tl']; ?></span>
                            <?php } ?>
                            <?php if($post['post_merged_id'] > 0) { ?> 
                              <i class="light-icon-arrow-forward-up red"></i>
                            <?php } ?>
                        </h1>
                        <div class="small lowercase">
                            <a class="gray" href="/u/<?= $post['login']; ?>">
                                <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
                                <span class="indent">
                                    <?= $post['login']; ?>
                                </span> 
                            </a> 
                            <span class="indent gray"> 
                                <?= $post['post_date_lang']; ?>
                                <?php if($post['modified']) { ?> 
                                    (<?= lang('ed'); ?>) 
                                <?php } ?>
                            </span>
                            <?php if ($uid['id']) { ?>
                                <?php if($uid['login'] == $post['login']  || $uid['trust_level'] == 5) { ?>
                                    <span class="indent">
                                    <span class="indent"></span>
                                        <a class="gray" href="/post/edit/<?= $post['post_id']; ?>">
                                            <i class="light-icon-edit middle"></i> 
                                        </a>
                                    </span>
                                <?php } ?>     
                                <?php if($uid['login'] == $post['login']) { ?>    
                                    <?php if($post['post_draft'] == 0) { ?>
                                        <?php if($post['my_post'] == $post['post_id']) { ?>
                                                <span class="mu_post gray">+ <?= lang('in-the-profile'); ?></span>
                                                <span class="indent"> &#183; </span>
                                        <?php } else { ?> 
                                            <a class="user-mypost indent gray" data-opt="1" data-post="<?= $post['post_id']; ?>">
                                                <span class="mu_post"><?= lang('in-the-profile'); ?></span>
                                                <span class="indent"> &#183;  </span>
                                            </a>
                                        <?php } ?> 
                                    <?php } ?>    
                                <?php } ?> 
                                <span class="add-favorite indent gray" data-id="<?= $post['post_id']; ?>" data-type="post">
                                    <?php if ($post['favorite_post']){ ?>
                                        <?= lang('remove-favorites'); ?>
                                    <?php } else { ?>
                                        <?= lang('add-favorites'); ?>
                                    <?php } ?> 
                                </span>
                                
                                <?php if($uid['trust_level'] ==5) { ?>
                                    <span class="indent"> &#183; </span>
                                    <span id="cm_dell" class="cm_add_link indent">
                                        <a data-type="post" data-id="<?= $post['post_id']; ?>" class="type-action gray">
                                            <?php if($post['post_is_deleted'] == 1) { ?>
                                                <?= lang('Recover'); ?>
                                            <?php } else { ?>
                                                <?= lang('Remove'); ?>
                                            <?php } ?>
                                        </a>
                                    </span>
                                    <small> &#183; <?= $post['post_hits_count']; ?></small>
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
                                <h3 class="recommend">ЛО</h3>
                                <span class="right">
                                    <a rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comment_<?= $lo['comment_id']; ?>">
                                        <i class="light-icon-checks red"></i>
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
                                <a data-hint="<?= $topic['topic_description']; ?>" class="tags" href="/topic/<?= $topic['topic_slug']; ?>">
                                    <?= $topic['topic_title']; ?>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    
                    </div>
                    <div class="post-full-footer">
                        <?= votes($uid['id'], $post, 'post'); ?> 
                        
                        <span class="right">
                            <i class="light-icon-messages middle"></i>
                            <?= $post['post_answers_count'] + $post['post_comments_count'] ?>
                        </span>
                        
                    </div>
                    <div>      
                        <?php if($post['post_type'] == 0 && $post['post_draft'] == 0) { ?>
                            <?php if ($uid['id'] > 0) { ?>
                               <?php if($post['post_closed'] == 0) { ?>
                                    <form id="add_answ" class="new_answer" action="/answer/create" accept-charset="UTF-8" method="post">
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
                                <textarea rows="5" class="darkening" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>" name="answer" id="answer"></textarea>
                                <div> 
                                    <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="button" disabled="disabled">
                                </div> 
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>

            <?php } else { ?>
                <div class="telo-detail-post dell">
                     <?= lang('Post deleted'); ?>...
                </div>   
            <?php } ?>
            
            <?php if($post['post_draft'] == 0) { ?> 
                <?php if($post['post_type'] == 0) { ?> 
                    <?php include TEMPLATE_DIR . '/post/comment-view.php'; ?>
                    <?php if($post['post_closed'] == 1) { ?> 
                        <p class="no-content gray"> 
                            <i class="light-icon-lock middle"></i> 
                            <span class="middle"><?= lang('The post is closed'); ?>...</span>
                        </p>
                    <?php } ?>
                <?php } else { ?>
                    <?php include TEMPLATE_DIR . '/post/questions-view.php'; ?>
                    <?php if($post['post_closed'] == 1) { ?>
                        <p class="no-content gray">
                            <i class="light-icon-lock middle"></i> 
                            <span class="middle"><?= lang('The question is closed'); ?>...</span>
                        </p>
                    <?php } ?>
                <?php } ?> 
            <?php } else { ?>   
                <p class="no-content gray">
                    <i class="light-icon-info-square middle"></i> 
                    <span class="middle"><?= lang('This is a draft'); ?>...</span>
                </p>
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
                    <a class="push gray" data-id="fb"><i class="light-icon-brand-facebook"></i></a>
                    <a class="push gray" data-id="vk">
                        <svg class="vk" xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24"><path class="st0" d="M13.162 18.994c.609 0 .858-.406.851-.915-.031-1.917.714-2.949 2.059-1.604 1.488 1.488 1.796 2.519 3.603 2.519h3.2c.808 0 1.126-.26 1.126-.668 0-.863-1.421-2.386-2.625-3.504-1.686-1.565-1.765-1.602-.313-3.486 1.801-2.339 4.157-5.336 2.073-5.336h-3.981c-.772 0-.828.435-1.103 1.083-.995 2.347-2.886 5.387-3.604 4.922-.751-.485-.407-2.406-.35-5.261.015-.754.011-1.271-1.141-1.539-.629-.145-1.241-.205-1.809-.205-2.273 0-3.841.953-2.95 1.119 1.571.293 1.42 3.692 1.054 5.16-.638 2.556-3.036-2.024-4.035-4.305-.241-.548-.315-.974-1.175-.974h-3.255c-.492 0-.787.16-.787.516 0 .602 2.96 6.72 5.786 9.77 2.756 2.975 5.48 2.708 7.376 2.708z"/></svg>
                    </a>
                    <a class="push gray" data-id="tw"><i class="light-icon-brand-twitter"></i></a>
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
                                <?php if($rec_post['post_answers_count'] !=0) { ?>
                                    <span class="green">+<?= $rec_post['post_answers_count'] ?></span>
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
  document.getElementsByClassName('tags').hint({
    pin: true, animate: true
  });
});
</script>  
<?php include TEMPLATE_DIR . '/footer.php'; ?> 