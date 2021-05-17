<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="post">
    <article class="post-full max-width">

        <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
   
            <div class="telo-detail_post<?php if($post['post_is_delete'] == 1) { ?> dell<?php } ?>">

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
                    <h1 class="titl">
                        <?= $post['post_title']; ?> 
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
                    </h1>
                    <div class="footer">
                        <img class="ava" alt="<?= $post['login']; ?>" src="/uploads/users/avatars/small/<?= $post['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                        </span>
                        <span class="date"> 
                            <?= $post['post_date']; ?>
                            <?php if($post['edit_date']) { ?> 
                                (<?= lang('ed'); ?>) 
                            <?php } ?>
                        </span>
                        <?php if ($uid['id']) { ?>
                            <span class="date"> 
                                <span class="otst"> | </span>
                            </span>

                            <?php if($uid['login'] == $post['login'] || $uid['trust_level'] == 5) { ?>
                                <span class="date">
                                    <a href="/post/edit/<?= $post['post_id']; ?>">
                                        <i class="icon pencil"></i>  
                                    </a>
                                </span>
                                <?php if($post['post_draft'] == 0) { ?>
                                    <span class="date"> 
                                        <span class="otst"> | </span>
                                    </span>
                                    <?php if($post['my_post'] == $post['post_id']) { ?>
                                            <span class="mu_post">+ <?= lang('in-the-profile'); ?></span>
                                            <span class="otst"> | </span>
                                    <?php } else { ?> 
                                        <a class="user-mypost" data-opt="1" data-post="<?= $post['post_id']; ?>">
                                            <span class="mu_post"><?= lang('in-the-profile'); ?></span>
                                            <span class="otst"> |  </span>
                                        </a>
                                    <?php } ?> 
                                <?php } ?>    
                            <?php } ?> 
                            
                            
                            <?php if ($post['favorite_post']){ ?>
                               <span class="otst">   </span>    
                               <span class="user-post-fav" data-post="<?= $post['post_id']; ?>">
                                    <span class="my_favorite"><?= lang('remove-favorites'); ?></span>
                               </span>   
                            <?php } else { ?>
                                <span class="otst">   </span>
                                <span class="user-post-fav" data-post="<?= $post['post_id']; ?>">
                                    <span class="my_favorite"><?= lang('add-favorites'); ?></span>
                                </span>
                            <?php } ?> 
                            
                            <?php if($uid['trust_level'] ==5) { ?>
                                <span class="otst"> | </span>
                                <span id="cm_dell" class="cm_add_link">
                                    <a data-post="<?= $post['post_id']; ?>" class="delpost">
                                        <?php if($post['post_is_delete'] == 1) { ?>
                                            <?= lang('Recover'); ?>
                                        <?php } else { ?>
                                            <?= lang('Remove'); ?>
                                        <?php } ?>
                                    </a>
                                </span>
                            <?php } ?>
                            
                        <?php } ?>
                    </div>  
                </div>
                <div class="post-body full">
                    <div class="post">
                        <?php if($post['post_content_img']) { ?> 
                            <div class="img-post-bl">
                                <img class="img-post" alt="<?= $post['post_title']; ?>" src="/uploads/posts/<?= $post['post_content_img']; ?>">
                            </div>    
                        <?php } ?>
                    
                        <?php if($post['post_thumb_img']) { ?> 
                            <img class="thumb" alt="<?= $post['post_url']; ?>" src="/uploads/posts/thumbnails/<?= $post['post_thumb_img']; ?>">
                        <?php } ?>
                    
                        <?= $post['post_content']; ?> 
                    </div> 
                    <?php if($lo) { ?>
                        <div class="lo-post">
                            <h3 class="lo">ЛО</h3>
                            <span class="right">
                                <a rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comm_<?= $lo['comment_id']; ?>">
                                    <i class="icon arrow-down"></i>
                                </a>
                            </span>
                            <?= $lo['comment_content']; ?> 
                        </div>     
                    <?php } ?>    
                    <?php if($post['post_url']) { ?> 
                        <span class="post_url_detal">
                            <?= lang('Website'); ?>: <a rel="nofollow noreferrer" href="<?= $post['post_url_full']; ?>">
                               <?= $post['post_url']; ?>
                            </a>
                        </span> 
                    <?php } ?>
                    
                    <?php if($post['post_type'] == 0 && $post['post_draft'] == 0) { ?>
                        <?php if ($uid['id']) { ?>
                           <?php if($post['post_closed'] == 0) { ?>
                                <form id="add_answ" class="new_answer" action="/answer/add" accept-charset="UTF-8" method="post">
                                <?= csrf_field() ?>
                                <div class="redactor">
                                    <div id="wmd-button-bar"></div>
                                    <textarea class="wmd-input h-150 w-95" rows="5" placeholder="<?= lang('write-something'); ?>..." name="answer" id="wmd-input"></textarea>
                                    <div class="clear"> 
                                        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                                        <input type="hidden" name="answ_id" id="answ_id" value="0">
                                        <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="answer-post">
                                    </div>
                                </div>    
                                </form>
                            <?php } ?>
                        <?php } else { ?>
                            <textarea rows="5" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>" name="answer" id="answer"></textarea>
                            <div> 
                                <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="answer-post" disabled="disabled">
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
                <?php include TEMPLATE_DIR . '/post/comm-view.php'; ?>
                <?php if($post['post_closed'] == 1) { ?> 
                    <p class="no-answer"><?= lang('post-closed'); ?>...</p>
                <?php } ?>
            <?php } else { ?>
                <?php include TEMPLATE_DIR . '/post/qa-view.php'; ?>
                <?php if($post['post_closed'] == 1) { ?>
                    <p class="no-answer">Вопрос закрыт...</p>
                <?php } ?>
            <?php } ?> 
        <?php } else { ?>   
            <p class="no-answer red">Это черновик...</p>
        <?php } ?>   
    </article>
</main>
<aside class="sidebar"> 

    <?php if($post['space_slug']) { ?>
        <div class="post-space-box">
            <img alt="<?= $post['space_name']; ?>" src="/uploads/spaces/<?= $post['space_img']; ?>">
            <a href="/s/<?= $post['space_slug']; ?>"><?= $post['space_name']; ?></a>
            <div class="post-space-box-desc">
                <?= $post['space_description']; ?>
            </div>
        </div>
    <?php } ?>
 
    <?php if($recommend) { ?> 
        <div>
            <h3 class="recommend"><?= lang('Recommended'); ?></h3>  
            <?php foreach ($recommend as  $post) { ?>
                <div class="recommend-telo">
                    <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                        <?= $post['post_title']; ?>  
                    </a>
                    <?php if($post['post_answers_num'] !=0) { ?>
                        <span class="n-comm">+<?= $post['post_answers_num'] ?></span>
                    <?php } ?> 
               </div>
            <?php } ?> 
        </div> 
    <?php } ?>   
</aside> 
<?php include TEMPLATE_DIR . '/footer.php'; ?> 