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
                                <icon name="trash"></icon>
                            <?php } ?>
                            <?php if($post['post_closed'] == 1) { ?> 
                                <icon name="lock"></icon>
                            <?php } ?>
                            <?php if($post['post_top'] == 1) { ?> 
                                <icon name="pin"></icon>
                            <?php } ?>
                            <?php if($post['post_lo'] > 0) { ?> 
                                <icon class="lo" name="trophy"></icon>
                            <?php } ?>
                    </h1>
                    <div class="footer">
                        <img class="ava" alt="<?= $post['login']; ?>" src="/uploads/avatar/small/<?= $post['avatar']; ?>">
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
                                        <icon name="pencil"></icon>  
                                    </a>
                                </span>
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
                <div class="post-body">
                
                    <?php if($post['post_thumb_img']) { ?> 
                        <img class="thumb" alt="<?= $post['post_url']; ?>" src="/uploads/thumbnails/<?= $post['post_thumb_img'] ?> ">
                    <?php } ?>
                
                    <div class="post">
                        <?= $post['content']; ?> 
                    </div> 
                    <?php if($lo) { ?>
                        <div class="lo-post">
                            <h3 class="lo">ЛО</h3>
                            <?= $lo['comment_content']; ?> 
                            <span class="right">
                                <a rel="nofollow" href="/posts/<?= $post['post_slug']; ?>#comm_<?= $lo['comment_id']; ?>">
                                    <icon name="arrow-down"></icon>
                                </a>
                            </span>
                        </div>     
                    <?php } ?>    
                    <?php if($post['post_url']) { ?> 
                        <span class="post_url_detal">
                            <?= lang('Website'); ?>: <a rel="nofollow noreferrer" href="<?= $post['post_url_full']; ?>">
                               <?= $post['post_url']; ?>
                            </a>
                        </span> 
                    <?php } ?>

                    <?php if ($uid['id']) { ?>
                       <?php if($post['post_closed'] == 0) { ?>
                            <form id="add_comm" class="new_comment" action="/comment/add" accept-charset="UTF-8" method="post">
                            <?= csrf_field() ?>
                                <textarea rows="5" placeholder="<?= lang('write-something'); ?>..." name="comment" id="comment"></textarea>
                                <div> 
                                    <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                                    <input type="hidden" name="comm_id" id="comm_id" value="0">
                                    <input type="submit" name="commit" value="<?= lang('Comment'); ?>" class="comment-post">
                                </div> 
                            </form>
                        <?php } ?>
                    <?php } else { ?>
                        <textarea rows="5" disabled="disabled" placeholder="<?= lang('no-auth-comm'); ?>" name="comment" id="comment"></textarea>
                        <div> 
                            <input type="submit" name="commit" value="<?= lang('Comment'); ?>" class="comment-post" disabled="disabled">
                        </div> 
                    <?php } ?>
                </div>
            </div>

        <?php } else { ?>
            <div class="telo-detail_post  dell">
                 <?= lang('post-delete'); ?>...
            </div>   
        <?php } ?>

        <?php if (!empty($comms)) { ?>
            <div class="comments">
                <h2><?= $post['post_comments'] ?> <?= $post['num_comments'] ?></h2>
                
                <?php foreach ($comms as  $comm) { ?>
                <div class="block-comments<?php if ($comm['level'] == 0) { ?> line<?php } ?>">
                    <?php if($comm['comment_del'] == 0) { ?>
                        <ol class="comment-telo<?php if ($comm['level'] == 0) { ?> one<?php } ?><?php if ($comm['level'] == 2) { ?> two<?php } ?><?php if ($comm['level'] > 2) { ?> three<?php } ?>"> 
                            <li class="comments_subtree" id="comm_<?= $comm['comment_id']; ?>">
                            <?php if($comm['comment_lo'] > 0) { ?> 
                                <span class="red right">
                                    <icon name="trophy"></icon>
                                </span>
                            <?php } ?>
                            <?php if (!$uid['id']) { ?> 
                                <div class="voters">
                                    <a rel="nofollow" href="/login"><div class="comm-up-id"></div></a>
                                    <div class="score"><?= $comm['comment_votes']; ?></div>
                                </div>
                            <?php } else { ?>
                                <?php if ($comm['votes_comm_user_id'] == $uid['id'] || $uid['id'] == $comm['comment_user_id']) { ?>
                                    <div class="voters active">
                                        <div class="comm-up-id"></div>
                                        <div class="score"><?= $comm['comment_votes']; ?></div>
                                    </div>
                                <?php } else { ?>
                                    <div id="up<?= $comm['comment_id']; ?>" class="voters">
                                        <div data-id="<?= $comm['comment_id']; ?>" class="comm-up-id"></div>
                                        <div class="score"><?= $comm['comment_votes']; ?></div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($comm['level'] != 0) { ?>
                                <div class="com-line"></div> 
                            <?php } ?>    
                                <div class="comm-telo">
                                    <div class="comm-header">
                                        <img alt="<?= $comm['login']; ?>" class="ava" src="/uploads/avatar/small/<?= $comm['avatar'] ?>">
                                        <span class="user"> 
                                            <a href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                        </span> 
                                        <span class="date">  
                                           <?= $comm['comment_date']; ?>
                                        </span>
                                        <?php if (empty($comm['edit'])) { ?> 
                                            <span class="date">  
                                               (<?= lang('ed'); ?>.)
                                            </span>
                                        <?php } ?>
                                        <?php if ($post['post_user_id'] == $comm['comment_user_id']) { ?>
                                            <span class="date ots">  
                                                <span class="authorpost">&#x21af;</span>
                                            </span>
                                        <?php } ?>
                                        <span class="date ots">
                                            <a rel="nofollow" href="/posts/<?= $post['post_slug']; ?>#comm_<?= $comm['comment_id']; ?>">#</a>
                                        </span>
                                        <?php if ($comm['level'] != 0) { ?> 
                                            <span class="date ots">
                                                <a rel="nofollow" href="/posts/<?= $post['post_slug']; ?>#comm_<?= $comm['comment_on']; ?>">&#8679;</a>
                                            </span>
                                        <?php } ?> 
                                        <?php if ($uid['trust_level'] == 5) { ?> 
                                            <span class="date ots">
                                                <?= $comm['comment_ip']; ?>
                                            </span>
                                        <?php } ?> 
                                    </div>
                                    <div class="comm-telo-body">
                                        <?= $comm['content'] ?> 
                                    </div>
                                </div>
                                <?php if($post['post_closed'] == 0) { ?> 
                                <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
                                    <span id="cm_add_link<?= $comm['comment_id']; ?>" class="cm_add_link">
                                        <a data-post_id="<?= $post['post_id']; ?>" data-id="<?= $comm['comment_id']; ?>" class="addcomm"><?= lang('Reply to'); ?></a>
                                    </span>
                                <?php } ?>
                                <?php } ?>
                                
                                <?php if($uid['id'] == $comm['comment_user_id'] || $uid['trust_level'] == 5) { ?>
                                    <span id="cm_edit" class="cm_add_link">
                                        <a data-id="<?= $comm['comment_id']; ?>" class="editcomm"><?= lang('Edit'); ?></a>
                                    </span>
                                <?php } ?>
                    
                                <?php if ($uid['id']) { ?>                    
                                    <?php if ($comm['favorite_comm']){ ?>
                                       <span class="user-comm-fav" data-comm="<?= $comm['comment_id']; ?>">
                                            <span class="favcomm"><?= lang('remove-favorites'); ?></span>
                                       </span>   
                                    <?php } else { ?>
                                        <span class="user-comm-fav" data-comm="<?= $comm['comment_id']; ?>">
                                            <span class="favcomm"><?= lang('add-favorites'); ?></span>
                                        </span>
                                    <?php } ?> 
                                <?php } ?>            
                                
                                <?php if($uid['trust_level'] == 5) { ?>
                                    <span id="cm_dell" class="cm_add_link">
                                        <a data-id="<?= $comm['comment_id']; ?>" class="delcomm"><?= lang('Remove'); ?></a>
                                    </span>
                                <?php } ?>
                              
                                <div id="cm_addentry<?= $comm['comment_id']; ?>" class="reply"></div> 
                            
                            </li>
                        </ol>
                                        
                    <?php } else { ?>    
                         <ol class="dell comment-telo<?php if ($comm['level'] == 0) { ?> one<?php } ?><?php if ($comm['level'] == 2) { ?> two<?php } ?><?php if ($comm['level'] > 2) { ?> three<?php } ?>"> 
                            <li class="comments_subtree" id="comm_<?= $comm['comment_id']; ?>">
                                <span class="comm-deletes">~ <?= lang('comment-deleted'); ?></span>
                            </li>
                        </ol>
                    <?php } ?>    
                </div>
                <?php } ?>
                 
            </div>
        <?php } else { ?>
            <div class="no-content"><?= lang('no-comment'); ?>...</div>
        <?php } ?>
  
        <?php if($post['post_closed'] == 1) { ?> 
            <div class="telo">
                <p class="info"><?= lang('post-closed'); ?>...</p>
            </div>
        <?php } ?>
       
    </article>
</main>
<aside id="sidebar"> 
    <?php if($recommend) { ?> 
        <div>
            <h3 class="recommend"><?= lang('Recommended'); ?></h3>  
            <?php foreach ($recommend as  $post) { ?>
                <div class="recommend-telo">
                    <a href="/posts/<?= $post['post_slug']; ?>">
                        <?= $post['post_title']; ?>  
                    </a>
                    <?php if($post['post_comments'] !=0) { ?>
                        <span class="n-comm">+<?= $post['post_comments'] ?></span>
                    <?php } ?> 
               </div>
            <?php } ?> 
        </div> 
    <?php } ?>   
</aside> 
<?php include TEMPLATE_DIR . '/footer.php'; ?> 