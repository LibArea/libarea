<?php if (!empty($answers)) { ?>
    <div class="answers white-box">
        <h2 class="lowercase"><?= $post['post_answers_num'] + $post['post_comments_num'] ?> <?= $post['num_comments'] ?></h2>
        
        <?php foreach ($answers as  $answ) { ?>
        <div class="block-answer">
            <?php if($answ['answer_del'] == 0) { ?>
                <div class="line"></div>
                <ol class="answer-telo"> 
                    <li class="answers_subtree" id="answer_<?= $answ['answer_id']; ?>">
                        <div class="container">
                            <div class="answ-telo">
                                <div class="answ-header small">
                                    <img alt="<?= $answ['login']; ?>" class="ava" src="<?= user_avatar_url($answ['avatar'], 'small'); ?>">
                                    <span class="user"> 
                                        <a class="date" href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a> 
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
                                        <a rel="nofollow" class="date" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#answer_<?= $answ['answer_id']; ?>">#</a>
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
                            <div class="comm-footer">
                                <?php if (!$uid['id']) { ?> 
                                    <div class="voters">
                                        <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                                        <div class="score">
                                            <?= $answ['answer_votes'] ? '+'.$answ['answer_votes'] : $answ['answer_votes']; ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                     <?php if ($answ['votes_answer_user_id'] == $uid['id'] || $uid['id'] == $answ['answer_user_id']) { ?>
                                        <div class="voters active">
                                            <div class="up-id"></div>
                                            <div class="score">
                                                <?= $answ['answer_votes'] ? '+'.$answ['answer_votes'] : $answ['answer_votes']; ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div id="up<?= $answ['answer_id']; ?>" class="voters">
                                            <div data-id="<?= $answ['answer_id']; ?>" data-type="answer" class="up-id"></div>
                                            <div class="score">
                                                <?= $answ['answer_votes'] ? '+'.$answ['answer_votes'] : $answ['answer_votes']; ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if($post['post_closed'] == 0) { ?> 
                                <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
                                    <span id="cm_add_link<?= $answ['answer_id']; ?>" class="cm_add_link">
                                        <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answ['answer_id']; ?>" class="add-comment bar"><?= lang('Reply'); ?></a>
                                    </span>
                                <?php } ?>
                                <?php } ?>
                                
                                <?php if($uid['id'] == $answ['answer_user_id'] || $uid['trust_level'] == 5) { ?>
                                    <span id="answer_edit" class="answer_add_link">
                                         <a class="editansw bar" href="/post/<?= $post['post_id'] ?>/answ/<?= $answ['answer_id']; ?>"><?= lang('Edit'); ?></a>
                                    </span>
                                <?php } ?>
                    
                                <?php if ($uid['id']) { ?>
                                    <?php if ($answ['favorite_uid']){ ?>
                                       <span class="user-answer-fav bar" data-answer="<?= $answ['answer_id']; ?>">
                                            <span class="favcomm"><?= lang('remove-favorites'); ?></span>
                                       </span>   
                                    <?php } else { ?>
                                        <span class="user-answer-fav bar" data-answer="<?= $answ['answer_id']; ?>">
                                            <span class="favcomm"><?= lang('add-favorites'); ?></span>
                                        </span>
                                    <?php } ?> 
                                <?php } ?>            
                                
                                <?php if($uid['trust_level'] == 5) { ?>
                                    <span id="answer_dell" class="answer_add_link">
                                        <a data-id="<?= $answ['answer_id']; ?>" class="del-answer bar"><?= lang('Remove'); ?></a>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                        <div id="answer_addentry<?= $answ['answer_id']; ?>" class="reply"></div> 
                    
                    </li>
                </ol>
                                
            <?php } else { ?>
            
                <?php if($uid['trust_level'] == 5) { ?>                    
                     <ol class="delleted small comm-telo"> 
                        <li class="comments_subtree" id="comment_<?= $answ['answer_id']; ?>">
                            <span class="comm-deletes nick">
                                <?= $answ['answer_content']; ?>
                                <?= lang('Answer'); ?> —  <?= $answ['login']; ?> 
                                <a data-id="<?= $answ['answer_id']; ?>" class="recover-answer right">
                                    <span><?= lang('Recover'); ?></span>
                                </a>
                            </span>
                        </li>
                    </ol>
               <?php } else { ?>  
                   <ol class="dell answer-telo"> 
                        <li class="answers_subtree" id="answer_<?= $answ['answer_id']; ?>">
                            <span class="answ-deletes">~ <?= lang('Comment deleted'); ?></span>
                        </li>
                    </ol>
               <?php } ?> 
               
            <?php } ?>    
        </div>
        
        
                <?php foreach ($answ['comm'] as  $comment) { ?>
                    <?php if($comment['comment_del'] == 0) { ?>
                        <ol class="comm-telo<?php if($comment['comment_comment_id'] > 0) { ?> step<?php } ?>"> 
                            <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
                                <div class="container">
                                    <div class="comm-telo">
                                        <div class="comm-header small"> 
                                            <img alt="<?= $comment['login']; ?>" class="ava" src="<?= user_avatar_url($comment['avatar'], 'small'); ?>">
                                            <span class="user"> 
                                                <a class="date" href="/u/<?= $comment['login']; ?>"><?= $comment['login']; ?></a> 
                                            </span> 
                                            <span class="date">  
                                               <?= $comment['comment_date']; ?>
                                            </span>
                                            <?php if ($post['post_user_id'] == $comment['comment_user_id']) { ?>
                                                <span class="date ots">  
                                                    <span class="authorpost">&#x21af;</span>
                                                </span>
                                            <?php } ?>
                                            <?php if($comment['comment_comment_id'] > 0) { ?>
                                                <span class="date ots">
                                                    <a class="date" rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comment_<?= $comment['comment_comment_id']; ?>">&uarr;</a>
                                                </span>
                                            <?php } else { ?>
                                                <span class="date ots">
                                                    <a class="date" rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#answer_<?= $comment['comment_answer_id']; ?>">&uarr;</a>
                                                </span>
                                            <?php } ?>
                                            <span class="date ots">
                                                <a class="date" rel="nofollow" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#comment_<?= $comment['comment_id']; ?>">#</a>
                                            </span>
                                            <?php if ($uid['trust_level'] == 5) { ?> 
                                                <span class="date ots">
                                                    <?= $comment['comment_ip']; ?>
                                                </span>
                                            <?php } ?> 
                                        </div>
                                        <div class="comm-telo-body">
                                            <?= lori\Content::text($comment['comment_content'], 'text'); ?>
                                        </div>
                                    </div>
                                    <div class="comm-footer">
                                        <?php if (!$uid['id']) { ?> 
                                            <div class="voters">
                                                <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                                                <div class="score">
                                                <?= $comment['comment_votes'] ? '+'.$comment['comment_votes'] : $comment['comment_votes']; ?>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <?php if ($comment['votes_comment_user_id'] == $uid['id'] || $uid['id'] == $comment['comment_user_id']) { ?>
                                                <div class="voters active">
                                                    <div class="up-id"></div>
                                                    <div class="score">
                                                        <?= $comment['comment_votes'] ? '+'.$comment['comment_votes'] : $comment['comment_votes']; ?>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <div id="up<?= $comment['comment_id']; ?>" class="voters">
                                                    <div data-id="<?= $comment['comment_id']; ?>" data-type="comment" class="up-id"></div>
                                                    <div class="score">
                                                        <?= $comment['comment_votes'] ? '+'.$comment['comment_votes'] : $comment['comment_votes']; ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if($post['post_closed'] == 0) { ?> 
                                        <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
                                            <span id="cm_add_link<?= $comment['comment_id']; ?>" class="cm_add_link">
                                                <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answ['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re bar">
                                                    <?= lang('Reply'); ?>
                                                </a>
                                            </span>
                                        <?php } ?>
                                        <?php } ?>
                                        
                                        <?php if (accessEditDelete($comment, $uid, 30) === true) { ?>
                                            <span id="comment_edit" class="cm_add_link">
                                                <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm bar">
                                                    <?= lang('Edit'); ?>
                                                </a>
                                            </span>
                                             
                                                <span id="comment_dell" class="cm_add_link">
                                                    <a data-comment_id="<?= $comment['comment_id']; ?>" class="del-comment bar">
                                                        <?= lang('Remove'); ?>
                                                    </a>
                                                </span>
                                         <?php } ?>
                                    </div>
                                </div>                                
                                <div id="comment_addentry<?= $comment['comment_id']; ?>" class="reply"></div> 
                            
                            </li>
                        </ol>
                
                    <?php } else { ?> 
                        <?php if($uid['trust_level'] == 5) { ?>                    
                             <ol class="delleted small comm-telo<?php if($comment['comment_comment_id'] > 0) { ?> step<?php } ?>"> 
                                <li class="comments_subtree" id="comment_<?= $comment['comment_id']; ?>">
                                    <span class="comm-deletes nick">
                                        <?= lori\Content::text($comment['comment_content'], 'line'); ?>
                                        —  <?= $comment['login']; ?> 
                                        <a data-id="<?= $comment['comment_id']; ?>" class="recover-comment right">
                                            <span><?= lang('Recover'); ?></span>
                                        </a>
                                    </span>
                                </li>
                            </ol>
                       <?php } ?>     
                    <?php } ?>  
                <?php } ?>   

        <?php } ?>
    </div>
<?php } else { ?>
    <?php if($post['post_closed'] != 1) { ?>
        <div class="no-content">
            <i class="icon info"></i> <?= lang('no-comment'); ?>...
        </div>
    <?php } ?>
<?php } ?>