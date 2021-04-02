<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">
     
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
                    <h1 class="titl"><?= $post['post_title']; ?></h1>
                
                    <div class="footer">
                        <img class="ava" src="/uploads/avatar/small/<?= $post['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                        </span>
                        <span class="date"> 
                            <?= $post['post_date']; ?>
                            <?php if($post['edit_date']) { ?> 
                                (изм. <?= $post['edit_date']; ?>) 
                            <?php } ?>
                        </span>
                        <span class="date"> 
                            <a class="space space_<?= $post['space_tip'] ?>" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                                <?= $post['space_name']; ?>
                            </a>
                            <span class="otst">   </span>
                        </span>
                        <?php if ($uid['id']) { ?>
                            <?php if($uid['login'] == $post['login'] || $uid['trust_level'] == 5) { ?>
                                <span class="date">
                                   &nbsp; <a href="/post/edit/<?= $post['post_id']; ?>">
                                        <svg class="md-icon moon">
                                            <use xlink:href="/assets/svg/icons.svg#edit"></use>
                                        </svg>
                                    </a>
                                </span>
                                <?php if($post['my_post'] == $post['post_id']) { ?>
                                        <span class="mu_post">+ в профиле</span>
                                        <span class="otst"> | </span>
                                <?php } else { ?> 
                                    <a class="user-mypost" data-opt="1" data-post="<?= $post['post_id']; ?>">
                                        <span class="mu_post">В профиль</span>
                                        <span class="otst"> |  </span>
                                    </a>
                                <?php } ?> 
                            <?php } ?> 
                            
                            <?php if ($post['favorite_post']){ ?>
                               <span class="otst">   </span>    
                               <span class="user-favorite" data-post="<?= $post['post_id']; ?>">
                                    <span class="mu_favorite">Убрать из избранного</span>
                               </span>   
                            <?php } else { ?>
                                <span class="otst">   </span>
                                <span class="user-favorite" data-post="<?= $post['post_id']; ?>">
                                    <span class="mu_favorite">В избранное</span>
                                </span>
                            <?php } ?> 
                            
                            <?php if($uid['trust_level'] ==5) { ?>
                                <span class="otst"> | </span>
                                <span id="cm_dell" class="cm_add_link">
                                    <a data-post="<?= $post['post_id']; ?>" class="delpost">
                                        <?php if($post['post_is_delete'] == 1) { ?>
                                            Восстановить
                                        <?php } else { ?>
                                            Удалить
                                        <?php } ?>
                                    </a>
                                </span>
                            <?php } ?>
                            
                        <?php } ?>
                    </div>  
                </div>
                <div class="left-ots">
                    <div class="post">
                        <?= $post['post_content']; ?> 
                    </div> 

                    <?php if($post['post_url']) { ?> 
                        <span class="post_url_detal">
                            Источник: <a rel="nofollow noreferrer" href="<?= $post['post_url_full']; ?>">
                               <?= $post['post_url']; ?>
                            </a>
                        </span> 
                    <?php } ?>

                    <?php if ($uid['id']) { ?>
                       <?php if($post['post_closed'] == 0) { ?>
                            <form id="add_comm" class="new_comment" action="/comment/add" accept-charset="UTF-8" method="post">
                            <?= csrf_field() ?>
                                <textarea rows="5" placeholder="Напишите, что нибудь..." name="comment" id="comment"></textarea>
                                <div> 
                                    <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                                    <input type="hidden" name="comm_id" id="comm_id" value="0">
                                    <input type="submit" name="commit" value="Комментарий" class="comment-post">
                                </div> 
                            </form>
                        <?php } ?>
                    <?php } else { ?>
                        <textarea rows="5" disabled="disabled" placeholder="Вы должны войти в систему, чтобы оставить комментарий." name="comment" id="comment"></textarea>
                        <div> 
                            <input type="submit" name="commit" value="Комментарий" class="comment-post" disabled="disabled">
                        </div> 
                    <?php } ?>
                </div>
            </div>

        <?php } else { ?>
            <div class="telo-detail_post  dell">
                 Пост удален...
            </div>   
        <?php } ?>



        <?php if (!empty($comms)) { ?>
            <div class="telo comments">
                <h2><?= $post['post_comments'] ?> <?= $post['num_comments'] ?></h2>
                
                <?php foreach ($comms as  $comm) { ?>
                <div class="block-comments">
                
                    <?php if($comm['comment_del'] == 0) { ?>
                    
                        <ol class="comment-telo<?php if ($comm['level'] == 0) { ?> one<?php } ?><?php if ($comm['level'] == 2) { ?> two<?php } ?><?php if ($comm['level'] > 2) { ?> three<?php } ?>"> 
                            <li class="comments_subtree" id="comm_<?= $comm['comment_id']; ?>">
                            
                            <?php if (!$uid['id']) { ?> 
                                <div class="voters">
                                    <a rel="nofollow" href="/login"><div class="comm-up-id"></div></a>
                                    <div class="score"><?= $comm['comment_votes']; ?></div>
                                </div>
                            <?php } else { ?>
                                <?php if ($comm['comm_vote_status'] || $uid['id'] == $comm['comment_user_id']) { ?>
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
                            
                                <div class="com-line<?php if($comm['comment_after'] != 1 and $comm['comment_on'] == 0) { ?> no-line<?php } ?>"></div> 
                                
                                <div class="comm-telo">
                                    <div class="comm-header">
                                        <img class="ava" src="/uploads/avatar/small/<?= $comm['avatar'] ?>">
                                        <span class="user"> 
                                            <a href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                        </span> 
                                        <span class="date">  
                                           <?= $comm['comment_date']; ?>
                                        </span>
                                        <?php if (empty($comm['edit'])) { ?> 
                                            <span class="date">  
                                               (изм.)
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

                                    </div>
                                    <div class="comm-telo-body">
                                        <?= $comm['content'] ?> 
                                    </div>
                                </div>
                                <?php if($post['post_closed'] == 0) { ?> 
                                <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
                                    <span id="cm_add_link<?php $comm['comment_id']; ?>" class="cm_add_link">
                                        <a data-post_id="<?= $post['post_id']; ?>" data-id="<?= $comm['comment_id']; ?>" class="addcomm">Ответить</a>
                                    </span>
                                <?php } ?>
                                <?php } ?>
                                
                                <?php if($uid['id'] == $comm['comment_user_id'] || $uid['trust_level'] == 5) { ?>
                                    <span id="cm_edit" class="cm_add_link">
                                        <a data-id="<?= $comm['comment_id']; ?>" class="editcomm">Изменить</a>
                                    </span>
                                <?php } ?>
                                
                                <?php if($uid['trust_level'] ==5) { ?>
                                    <span id="cm_dell" class="cm_add_link">
                                        <a data-id="<?= $comm['comment_id']; ?>" class="delcomm">Удалить</a>
                                    </span>
                                <?php } ?>
                              
                                <div id="cm_addentry<?= $comm['comment_id']; ?>" class="reply"></div> 
                            
                            </li>
                        </ol>
                                        
                    
                    <?php } else { ?>    
                         <ol class="dell comment-telo<?php if ($comm['level'] == 0) { ?> one<?php } ?><?php if ($comm['level'] == 2) { ?> two<?php } ?><?php if ($comm['level'] > 2) { ?> three<?php } ?>"> 
                            <li class="comments_subtree" id="comm_<?= $comm['comment_id']; ?>">
                                ~ Комментарий удален
                            </li>
                        </ol>
                    <?php } ?>    
                </div>
                <?php } ?>
                 
            </div>
        <?php } else { ?>
            <div class="telo">
                <p class="info">К сожалению комментариев пока нет...</p>
            </div>
        <?php } ?>
  
        <?php if($post['post_closed'] == 1) { ?> 
            <div class="telo">
                <p class="info">Пост закрыт...</p>
            </div>
        <?php } ?>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 