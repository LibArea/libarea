<?php if (!empty($answers)) { ?>
    <div class="answers">
        <h2> <?= $post['post_answers_num'] ?> <?= $post['num_answers'] ?></h2>
        
        <?php foreach ($answers as  $answ) { ?>
        <div class="block-answer">
            <?php if($answ['answer_del'] == 0) { ?>
            
            <?php if($uid['id'] == $answ['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>
            
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
                        <div class="answ-telo qa-answ">
                            <?= $answ['answer_content'] ?>
                        </div>
                        <div class="qa-inline">
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
                        </div> 
                            <div class="qa-footer">
                                <?php if ($uid['trust_level'] == 5) { ?> 
                                    <div class="qa-date">
                                        <?= $answ['answer_ip']; ?>
                                    </div>
                                <?php } ?> 
                                <div class="qa-ava">
                                    <img alt="<?= $answ['login']; ?>" src="/uploads/avatar/<?= $answ['avatar'] ?>">
                                </div>
                                <div class="qa-ava-info"> 
                                    <a class="qa-login" href="/u/<?= $answ['login']; ?>"><?= $answ['login']; ?></a> 
                                    <?= $answ['answer_date']; ?> 
                                    <?php if (empty($answ['edit'])) { ?>
                                        (<?= lang('ed'); ?>.)
                                    <?php } ?>
                                </div>
                            </div>
                             
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
                     
                    <ol class="comm-telo qa-comm"> 
                        <li class="comm_subtree" id="comm_<?= $comm['comment_id']; ?>">
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
                                    
                                </div>
                            </div>
                            <div id="comm_addentry<?= $comm['comment_id']; ?>" class="reply"></div> 
                        
                        </li>
                    </ol>
            
                <?php } else { ?>    
         
                <?php } ?>  
            <?php } ?>   

        <?php } ?>
    </div>
<?php } else { ?>
    <?php if($post['post_closed'] != 1) { ?>
        <div class="no-answer"><?= lang('no-answer'); ?>... 
        <br> Напишите свой первый ответ.</div>
    <?php } ?>
<?php } ?>

<?php if(!empty($otvet)) { ?>

    <div class="no-answer">Вы уже ответили на этот вопрос...</div>

<?php } else { ?>
    <?php if ($uid['id']) { ?>
            <?php if($post['post_closed'] == 0) { ?>
                <form id="add_answ" action="/answer/add" accept-charset="UTF-8" method="post">
                <?= csrf_field() ?>
                <br>
                    <textarea class="editable" name="answer" id="answer"></textarea>
                    <div> 
                        <input type="hidden" name="post_id" id="post_id" value="<?= $post['post_id']; ?>">
                        <input type="hidden" name="answ_id" id="answ_id" value="0">
                        <input type="submit" name="answit" value="<?= lang('Reply'); ?>" class="answer-post">
                    </div> 
                </form>
                <script src="/assets/js/editor/js/medium-editor.js"></script>
                <script src="/assets/js/editor.js"></script> 
            <?php } ?>
        <?php } else { ?>
            <br />
            <textarea rows="5" disabled="disabled" placeholder="<?= lang('no-auth-answ'); ?>" name="answer" id="answer"></textarea>
            <div>
                Для ответа вы <a href="/login">можете авторизироваться</a>.
            </div> 
    <?php } ?>
<?php }  ?>    