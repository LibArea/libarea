<ol class="comment-telo<?php if ($comm['level'] == 0) { ?> one<?php } ?><?php if ($comm['level'] == 2) { ?> two<?php } ?><?php if ($comm['level'] > 2) { ?> three<?php } ?>"> 
    <li class="comments_subtree" id="comm_<?= $comm['comment_id']; ?>">

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

        <div class="comm-telo">
            <div class="comm-header">
                <img class="ava" src="/uploads/avatar/small/<?= $comm['avatar'] ?>">
                <span class="user"> 
                    <a href="/u/<?= $comm['login']; ?>"><?= $comm['login']; ?></a> 
                </span> 
                <span class="date">  
                   <?= $comm['date']; ?>
                </span>
                <span class="date">  
                    <?php if ($data['post']['post_user_id'] == $comm['comment_user_id']) { ?><span class="authorpost">&#x21af;</span> <?php } ?>
                </span>
                <span class="date">
                    <a rel="nofollow" href="/post/<?= $data['post']['post_slug']; ?>#comm_<?= $comm['comment_id']; ?>">#</a>
                </span>
                <?php if ($comm['level'] != 0) { ?> 
                    <span class="date">
                        <a rel="nofollow" href="/post/<?= $data['post']['post_slug']; ?>#comm_<?= $comm['comment_on']; ?>">&#8679;</a>
                    </span>
                <?php } ?> 
            </div>
            <div class="comm-telo-body">
                <?= $comm['content'] ?> 
            </div>
        </div>
        <span id="cm_add_link<?php $comm['comment_id']; ?>" class="cm_add_link">
            <a data-post_id="<?= $data['post']['id']; ?>" data-id="<?= $comm['comment_id']; ?>" class="addcomm">Ответить</a>
        </span>

        <div id="cm_addentry<?= $comm['comment_id']; ?>" class="reply"></div> 
    
    </li>
</ol>