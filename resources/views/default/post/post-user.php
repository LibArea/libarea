<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <h1 class="top"><?= $data['h1']; ?></h1>

    <div class="telo posts">
        <?php if (!empty($posts)) { ?>
      
            <?php foreach ($posts as $post) { ?> 
          
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
            
                <div class="post-telo">
                    <a class="u-url" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                        <h3 class="titl"><?= $post['post_title']; ?></h3>
                    </a>
                    <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                        <?= $post['space_name']; ?>
                    </a>
                    
                    <div class="footer">
                        <img class="ava" src="/uploads/users/avatars/small/<?= $post['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                        </span>
                        <span class="date"> 
                            <?= $post['post_date']; ?>
                        </span>
                        <?php if($post['post_answers_num'] !=0) { ?> 
                            <span class="otst"> | </span>
                            ответов (<?= $post['post_answers_num'] ?>) 
                        <?php } ?>
                    </div>  
                </div>
       
            <?php } ?>

        <?php } else { ?>

            <div class="no-content"><?= lang('no-post'); ?>...</div>

        <?php } ?>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 