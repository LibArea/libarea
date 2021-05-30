<?php if (!empty($answers)) { ?>
    <div class="answers">
        <h2 class="lowercase"><?= $post['post_answers_num'] + $post['post_comments_num'] ?> <?= $post['num_comments'] ?></h2>
        
        <?php foreach ($answers as  $answ) { ?>
        <div class="block-answer">
            <?php if($answ['answer_del'] == 0) { ?>
                <div class="line"></div>
                <ol class="answer-telo"> 
                    <li class="answers_subtree" id="answ_<?= $answ['answer_id']; ?>">
          
                    <?php if (!$uid['id']) { ?> 
                        <div class="voters">
                            <a rel="nofollow" href="/login"><div class="answ-up-id"></div></a>
                            <div class="score"><?= $answ['answer_votes']; ?></div>
                        </div>
                    <?php } else { ?>
                        <?php if ($answ['votes_answ_user_id'] == $uid['id'] || $uid['id'] == $answ['answer_user_id']) { ?>
                            <div class="voters active">
                                <div class="answ-up-id"></div>
                                <div class="score"><?= $answ['answer_votes']; ?></div>
                            </div>
                        <?php } else { ?>
                            <div id="up<?= $answ['answer_id']; ?>" class="voters">
                                <div data-id="<?= $answ['answer_id']; ?>" class="answ-up-id"></div>
                                <div class="score"><?= $answ['answer_votes']; ?></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                        <div class="answ-telo">
                            <div class="answ-header">
                                <img alt="<?= $answ['login']; ?>" class="ava" src="/uploads/users/avatars/small/<?= $answ['avatar'] ?>">
                                <span class="user"> 
                                    <a href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a> 
                                </span> 
                                <span class="date">  
                                   <?= $answ['answer_date']; ?>
                                </span>
                                <?php if (empty($answ['edit'])) { ?> 
                                    <span class="date">  
                                       (<?= lang('ed'); ?>.)
                                    </span>
                                <?php } ?>
                                <?php if ($post['post_user_id'] == $answ['answer_user_id']) { ?>
                                    <span class="date ots">  
                                        <span class="authorpost">&#x21af;</span>
                                    </span>
                                <?php } ?>
                                <span class="date ots">
                                    <a rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#answ_<?= $answ['answer_id']; ?>">#</a>
                                </span>
                                <?php if ($uid['trust_level'] == 5) { ?> 
                                    <span class="date ots">
                                        <?= $answ['answer_ip']; ?>
                                    </span>
                                <?php } ?> 
                            </div>
                            <div class="answ-telo-body">
                                <?= $answ['answer_content'] ?> 
                            </div>
                        </div>
                        <?php if($post['post_closed'] == 0) { ?> 
                        <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
                            <span id="cm_add_link<?= $answ['answer_id']; ?>" class="cm_add_link">
                                <a data-post_id="<?= $post['post_id']; ?>" data-answ_id="<?= $answ['answer_id']; ?>" class="addcomm"><?= lang('Reply'); ?></a>
                            </span>
                        <?php } ?>
                        <?php } ?>
                        
                        <?php if($uid['id'] == $answ['answer_user_id'] || $uid['trust_level'] == 5) { ?>
                            <span id="answ_edit" class="answ_add_link">
                                 <a class="editansw" href="/post/<?= $post['post_id'] ?>/answ/<?= $answ['answer_id']; ?>"><?= lang('Edit'); ?></a>
                            </span>
                        <?php } ?>
            
                        <?php if ($uid['id']) { ?>                    
                            <?php if ($answ['favorite_answ']){ ?>
                               <span class="user-answ-fav" data-answ="<?= $answ['answer_id']; ?>">
                                    <span class="favcomm"><?= lang('remove-favorites'); ?></span>
                               </span>   
                            <?php } else { ?>
                                <span class="user-answ-fav" data-answ="<?= $answ['answer_id']; ?>">
                                    <span class="favcomm"><?= lang('add-favorites'); ?></span>
                                </span>
                            <?php } ?> 
                        <?php } ?>            
                        
                        <?php if($uid['trust_level'] == 5) { ?>
                            <span id="answ_dell" class="answ_add_link">
                                <a data-id="<?= $answ['answer_id']; ?>" class="delansw"><?= lang('Remove'); ?></a>
                            </span>
                        <?php } ?>
                      
                        <div id="answ_addentry<?= $answ['answer_id']; ?>" class="reply"></div> 
                    
                    </li>
                </ol>
                                
            <?php } else { ?>    
                 <ol class="dell answer-telo"> 
                    <li class="answers_subtree" id="answ_<?= $answ['answer_id']; ?>">
                        <span class="answ-deletes">~ <?= lang('answer-deleted'); ?></span>
                    </li>
                </ol>
            <?php } ?>    
        </div>
        
        
                <?php foreach ($answ['comm'] as  $comm) { ?>
                    <?php if($comm['comment_del'] == 0) { ?>
                        <ol class="comm-telo<?php if($comm['comment_comm_id'] > 0) { ?> step<?php } ?>"> 
                            <li class="comm_subtree" id="comm_<?= $comm['comment_id']; ?>">
          
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
                                <div class="comm-telo">
                                    <div class="comm-header">
                                        <img alt="<?= $comm['login']; ?>" class="ava" src="/uploads/users/avatars/small/<?= $comm['avatar'] ?>">
                                        <span class="user"> 
                                            <a href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                        </span> 
                                        <span class="date">  
                                           <?= $comm['comment_date']; ?>
                                        </span>
                                        <?php if ($post['post_user_id'] == $comm['comment_user_id']) { ?>
                                            <span class="date ots">  
                                                <span class="authorpost">&#x21af;</span>
                                            </span>
                                        <?php } ?>
                                        <?php if($comm['comment_comm_id'] > 0) { ?>
                                            <span class="date ots">
                                                <a rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comm_<?= $comm['comment_comm_id']; ?>">&uarr;</a>
                                            </span>
                                        <?php } else { ?>
                                            <span class="date ots">
                                                <a rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#answ_<?= $comm['comment_answ_id']; ?>">&uarr;</a>
                                            </span>
                                        <?php } ?>
                                        
                                        
                                        <span class="date ots">
                                            <a rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comm_<?= $comm['comment_id']; ?>">#</a>
                                        </span>
                                        <?php if ($uid['trust_level'] == 5) { ?> 
                                            <span class="date ots">
                                                <?= $comm['comment_ip']; ?>
                                            </span>
                                        <?php } ?> 
                                    </div>
                                    <div class="comm-telo-body">
                                        <?= $comm['comment_content'] ?> 
                                    </div>
                                </div>
                                
                                <?php if($post['post_closed'] == 0) { ?> 
                                <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
                                    <span id="cm_add_link<?= $comm['comment_id']; ?>" class="cm_add_link">
                                        <a data-post_id="<?= $post['post_id']; ?>" data-answ_id="<?= $answ['answer_id']; ?>" data-comm_id="<?= $comm['comment_id']; ?>" class="addcomm_re"><?= lang('Reply'); ?></a>
                                    </span>
                                <?php } ?>
                                <?php } ?>
                                
                                <?php if($uid['id'] == $comm['comment_user_id'] || $uid['trust_level'] == 5) { ?>
                                    <span id="comm_edit" class="cm_add_link">
                                        <a data-comm_id="<?= $comm['comment_id']; ?>" class="editcomm"><?= lang('Edit'); ?></a>
                                    </span>
                                <?php } ?>

                                <?php if($uid['trust_level'] == 5) { ?>
                                    <span id="comm_dell" class="cm_add_link">
                                        <a data-comm_id="<?= $comm['comment_id']; ?>" class="delcomm"><?= lang('Remove'); ?></a>
                                    </span>
                                <?php } ?>
                              
                                <div id="comm_addentry<?= $comm['comment_id']; ?>" class="reply"></div> 
                            
                            </li>
                        </ol>
                
                    <?php } else { ?>    
                         <ol class="dell comment-telo<?php if ($comm['level'] == 0) { ?> one<?php } ?><?php if ($comm['level'] == 2) { ?> two<?php } ?><?php if ($comm['level'] > 2) { ?> three<?php } ?>"> 
                            <li class="comments_subtree" id="comm_<?= $comm['comment_id']; ?>">
                                <span class="comm-deletes">~ <?= lang('comment-deleted'); ?></span>
                            </li>
                        </ol>
                    <?php } ?>  
                <?php } ?>   

        <?php } ?>
    </div>
<?php } else { ?>
    <?php if($post['post_closed'] != 1) { ?>
        <div class="no-answer">
            <i class="icon info"></i> <?= lang('no-comment'); ?>...
        </div>
    <?php } ?>
<?php } ?>