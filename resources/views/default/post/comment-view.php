<?php if (!empty($answers)) { ?>
    <div class="answers white-box">
        <h2 class="lowercase"><?= $post['post_answers_count'] + $post['post_comments_count'] ?> <?= $post['num_comments'] ?></h2>
        
        <?php foreach ($answers as  $answer) { ?>
        <div class="block-answer">
            <?php if($answer['answer_is_deleted'] == 0) { ?>
                <div class="line"></div>
                <ol class="answer-telo"> 
                    <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
                        <div class="container">
                            <div class="answ-telo">
                                <div class="answ-header small">
                                    <?= user_avatar_img($answer['avatar'], 'small', $answer['login'], 'ava'); ?>
                                    <span class="user"> 
                                        <a class="date" href="/u/<?= $answer['login']; ?>"><?= $answer['login']; ?></a> 
                                    </span> 
                                    <span class="date">  
                                       <?= $answer['answer_date']; ?>
                                    </span>
                                    <?php if (empty($answer['edit'])) { ?> 
                                        <span class="date">  
                                           (<?= lang('ed'); ?>.)
                                        </span>
                                    <?php } ?>
                                    <?php if ($post['post_user_id'] == $answer['answer_user_id']) { ?>
                                        <span class="date ots">  
                                            <span class="authorpost">&#x21af;</span>
                                        </span>
                                    <?php } ?>
                                    <span class="date ots">
                                        <a rel="nofollow" class="date" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>#answer_<?= $answer['answer_id']; ?>">#</a>
                                    </span>
                                    <?php if ($uid['trust_level'] == 5) { ?> 
                                        <span class="date ots">
                                            <?= $answer['answer_ip']; ?>
                                        </span>
                                    <?php } ?> 
                                </div>
                                <div class="answ-telo-body">
                                    <?= $answer['answer_content'] ?> 
                                </div>
                            </div>
                            <div class="comm-footer">
                                <?php if (!$uid['id']) { ?> 
                                    <div class="voters">
                                        <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                                        <div class="score">
                                            <?= $answer['answer_votes'] ? '+'.$answer['answer_votes'] : $answer['answer_votes']; ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                     <?php if ($answer['votes_answer_user_id'] == $uid['id'] || $uid['id'] == $answer['answer_user_id']) { ?>
                                        <div class="voters active">
                                            <div class="up-id"></div>
                                            <div class="score">
                                                <?= $answer['answer_votes'] ? '+'.$answer['answer_votes'] : $answer['answer_votes']; ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div id="up<?= $answer['answer_id']; ?>" class="voters">
                                            <div data-id="<?= $answer['answer_id']; ?>" data-type="answer" class="up-id"></div>
                                            <div class="score">
                                                <?= $answer['answer_votes'] ? '+'.$answer['answer_votes'] : $answer['answer_votes']; ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if($post['post_closed'] == 0) { ?> 
                                <?php if($post['post_is_deleted'] == 0 || $uid['trust_level'] == 5) { ?>
                                    <span id="cm_add_link<?= $answer['answer_id']; ?>" class="cm_add_link">
                                        <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment bar"><?= lang('Reply'); ?></a>
                                    </span>
                                <?php } ?>
                                <?php } ?>
                                
                                <?php if($uid['id'] == $answer['answer_user_id'] || $uid['trust_level'] == 5) { ?>
                                    <?php if($answer['answer_after'] == 0 || $uid['trust_level'] == 5) { ?>
                                        <span id="answer_edit" class="answer_add_link">
                                            <a class="editansw bar" href="/answer/edit/<?= $answer['answer_id']; ?>">   <?= lang('Edit'); ?>
                                            </a>
                                        </span>
                                    <?php } ?>
                                <?php } ?>
                    
                                <?php if ($uid['id']) { ?>
                                   <span class="add-favorite bar" data-id="<?= $answer['answer_id']; ?>" data-type="answer">
                                        <?php if ($answer['favorite_user_id']){ ?>
                                            <?= lang('remove-favorites'); ?>
                                        <?php } else { ?>
                                            <?= lang('add-favorites'); ?>
                                        <?php } ?> 
                                   </span>   
                                <?php } ?>            
                                
                                <?php if($uid['trust_level'] == 5) { ?>
                                    <span id="answer_dell" class="answer_add_link">
                                        <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action bar">
                                            <?= lang('Remove'); ?>
                                        </a>
                                    </span>
                                <?php } ?>
                            </div>
                        </div>
                        <div id="answer_addentry<?= $answer['answer_id']; ?>" class="reply"></div> 
                    
                    </li>
                </ol>
                                
            <?php } else { ?>
            
                <?php if($uid['trust_level'] == 5) { ?>                    
                     <ol class="delleted small comm-telo"> 
                        <li class="comments_subtree" id="comment_<?= $answer['answer_id']; ?>">
                            <span class="comm-deletes nick">
                                <?= $answer['answer_content']; ?>
                                <?= lang('Answer'); ?> —  <?= $answer['login']; ?> 
                                <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action right">
                                    <span><?= lang('Recover'); ?></span>
                                </a>
                            </span>
                        </li>
                    </ol>
               <?php } else { ?>  
                   <ol class="dell answer-telo"> 
                        <li class="answers_subtree" id="answer_<?= $answer['answer_id']; ?>">
                            <span class="answ-deletes">~ <?= lang('Comment deleted'); ?></span>
                        </li>
                    </ol>
               <?php } ?> 
               
            <?php } ?>    
        </div>
       
                <?php foreach ($answer['comm'] as  $comment) { ?>
                    <?php if($comment['comment_is_deleted'] == 0) { ?>
                    
                        <ol class="comm-telo<?php if($comment['comment_comment_id'] > 0) { ?> step<?php } ?>"> 
                            <li class="comment_subtree" id="comment_<?= $comment['comment_id']; ?>">
                                <div class="container">
                                    <div class="comm-telo">
                                        <div class="comm-header small"> 
                                            <?= user_avatar_img($comment['avatar'], 'small', $comment['login'], 'ava'); ?>
                                            <span class="user"> 
                                                <a class="date" href="/u/<?= $comment['login']; ?>"><?= $comment['login']; ?></a> 
                                            </span> 
                                            <span class="date">  
                                               <?= lang_date($comment['comment_date']); ?>
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
                                            <?= lori\Content::text($comment['comment_content'], 'line'); ?>
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
                                        <?php if($post['post_is_deleted'] == 0 || $uid['trust_level'] == 5) { ?>
                                            <span id="cm_add_link<?= $comment['comment_id']; ?>" class="cm_add_link">
                                                <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment-re bar">
                                                    <?= lang('Reply'); ?>
                                                </a>
                                            </span>
                                        <?php } ?>
                                        <?php } ?>
                                        
                                        <?php if (accessСheck($comment, 'comment', $uid, 1, 30) === true) { ?>
                                            <span id="comment_edit" class="cm_add_link">
                                                <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm bar">
                                                    <?= lang('Edit'); ?>
                                                </a>
                                            </span>
                                             
                                            <span id="comment_dell" class="cm_add_link">
                                                <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action bar">
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
                        <?php if(accessСheck($comment, 'comment', $uid, 1, 30) === true) { ?>                    
                             <ol class="delleted small comm-telo<?php if($comment['comment_comment_id'] > 0) { ?> step<?php } ?>"> 
                                <li class="comments_subtree" id="comment_<?= $comment['comment_id']; ?>">
                                    <span class="comm-deletes nick">
                                        <?= lori\Content::text($comment['comment_content'], 'line'); ?>
                                        —  <?= $comment['login']; ?> 
                                        <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action right">
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