<?php if (!empty($answers)) { ?>
    <div class="answers white-box">
        <h2 class="lowercase"> <?= $post['post_answers_num'] ?> <?= $post['num_answers'] ?></h2>
        
        <?php foreach ($answers as  $answ) { ?>
        <div class="block-answer">
            <?php if($answ['answer_del'] == 0) { ?>
            
            <?php if($uid['id'] == $answ['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>
            
                <div class="line"></div>
                <ol class="answer-telo"> 
                    <li class="answers_subtree" id="answer_<?= $answ['answer_id']; ?>">
                        <div class="answ-telo qa-answ">
                            <div class="qa-footer">
                                <div class="qa-ava">
                                    <img alt="<?= $answ['login']; ?>" src="<?= user_avatar_url($answ['avatar'], 'max'); ?>">
                                </div>
                                <div class="qa-ava-info"> 
                                    <div class="qa-data-info"> 
                                        <?= $answ['answer_date']; ?> 
                                        <?php if (empty($answ['edit'])) { ?>
                                            (<?= lang('ed'); ?>.)
                                        <?php } ?>
                                        <?php if ($uid['trust_level'] == 5) { ?> 
                                            <?= $answ['answer_ip']; ?>
                                        <?php } ?> 
                                    </div>
                                    <a class="qa-login" href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a> 
                                </div>
                            </div>
                        
                        
                            <?= $answ['answer_content'] ?>
                        </div>
                        <div class="answer-footer">
                            <?php if($uid['trust_level'] >= Lori\Config::get(Lori\Config::PARAM_TL_ADD_COMM_QA)) { ?>
                            <?php if($post['post_closed'] == 0) { ?> 
                            <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
                                <span id="cm_add_link<?= $answ['answer_id']; ?>" class="cm_add_link">
                                    <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answ['answer_id']; ?>" class="add-comment bar"><?= lang('Reply'); ?></a>
                                </span>
                            <?php } ?>
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
                                <span class="indent"></span>
                                <span class="indent"></span>
                                <span id="answer_dell" class="answer_add_link">
                                    <a data-id="<?= $answ['answer_id']; ?>" class="del-answer bar"><?= lang('Remove'); ?></a>
                                </span>
                            <?php } ?>
                            
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
                            
                        </div> 
                        <div id="answer_addentry<?= $answ['answer_id']; ?>" class="reply"></div> 
                    </li>
                </ol>
                                
            <?php } else { ?>    
                 <ol class="dell answer-telo"> 
                    <li class="answers_subtree" id="answer_<?= $answ['answer_id']; ?>">
                        <span class="answ-deletes">~ <?= lang('answer-deleted'); ?></span>
                    </li>
                </ol>
            <?php } ?>    
        </div>

            <?php foreach ($answ['comm'] as  $comm) { ?>
                <?php if($comm['comment_del'] == 0) { ?>
                     
                    <ol class="comm-telo qa-comm"> 
                        <li class="comment_subtree" id="comment_<?= $comm['comment_id']; ?>">
                            <div class="line-qa"></div>
                            <div class="comm-telo">
                                <div class="comm-telo-body">
                                    <?= $comm['comment_content'] ?>  
                                    <span class="qa-comm-info"> 
                                        —  <a class="qa-user" href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                                       &nbsp; <?= $comm['comment_date']; ?>
                                        <?php if ($uid['trust_level'] == 5) { ?> 
                                            &nbsp; <?= $comm['comment_ip']; ?>
                                        <?php } ?> 
                                     </span>

                                    <?php if($uid['trust_level'] >= Lori\Config::get(Lori\Config::PARAM_TL_ADD_COMM_QA)) { ?>
                                    <?php if($post['post_closed'] == 0) { ?>
                                    <?php if($post['post_is_delete'] == 0 || $uid['trust_level'] == 5) { ?>
                                        <span id="cm_add_link<?= $comm['comment_id']; ?>" class="cm_add_link">
                                            <a data-post_id="<?= $post['post_id']; ?>" data-answer_id="<?= $answ['answer_id']; ?>" data-comment_id="<?= $comm['comment_id']; ?>" class="add-comment-re">
                                                <?= lang('Reply'); ?>
                                            </a>
                                        </span>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php } ?>
                                    
                                    <?php if($uid['id'] == $comm['comment_user_id'] || $uid['trust_level'] == 5) { ?>
                                        <span id="comment_edit" class="cm_add_link">
                                            <a data-comment_id="<?= $comm['comment_id']; ?>" class="editcomm">
                                                <?= lang('Edit'); ?>
                                            </a>
                                        </span>
                                    <?php } ?>

                                    <?php if($uid['trust_level'] == 5) { ?>
                                        <span id="comment_dell" class="cm_add_link">
                                            <a data-comment_id="<?= $comm['comment_id']; ?>" class="del-comment">
                                                <?= lang('Remove'); ?>
                                            </a>
                                        </span>
                                    <?php } ?>
                                    
                                </div>
                            </div>
                            <div id="comment_addentry<?= $comm['comment_id']; ?>" class="reply"></div> 
                        
                        </li>
                    </ol>
            
                <?php } else { ?>    
         
                <?php } ?>  
            <?php } ?>   

        <?php } ?>
    </div>
<?php } else { ?>
    <?php if($post['post_closed'] != 1) { ?>
        <div class="no-content">
            <i class="icon info"></i> <?= lang('no-answer'); ?>... 
        </div>
    <?php } ?>
<?php } ?>

<?php if(!empty($otvet)) { ?>

    <div class="no-content"><?= lang('you-question-no'); ?>...</div>

<?php } else { ?>
    <?php if ($uid['id']) { ?>
            <?php if($post['post_closed'] == 0) { ?>
                <form id="add_answ" action="/answer/add" accept-charset="UTF-8" method="post">
                <?= csrf_field() ?>
                <div class="redactor">
                    <div id="wmd-button-bar"></div>
                    <textarea minlength="6" class="wmd-input h-150 w-95" name="answer" id="wmd-input"></textarea>
                    <div class="clear"> 
                        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                        <input type="hidden" name="answer_id" id="answer_id" value="0">
                        <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="answer-post">
                    </div> 
                </div>     
                </form>

            <?php } ?>
        <?php } else { ?>
            <br />
            <textarea rows="5" disabled="disabled" placeholder="<?= lang('no-auth-answ'); ?>" name="answer" id="answer"></textarea>
            <div>
                <?= lang('no-auth-login'); ?>...
            </div> 
    <?php } ?>
<?php }  ?>    