<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <h1 class="top"><?php echo $data['h1']; ?></h1>

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
                    <a class="u-url" href="/posts/<?php echo $post['post_slug']; ?>">
                        <h3 class="titl"><?php echo $post['post_title']; ?></h3>
                    </a>
                    <div class="space-color space_<?= $post['space_color'] ?>"></div>
                    <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                        <?= $post['space_name']; ?>
                    </a>
                    
                    <div class="footer">
                        <img class="ava" src="/uploads/avatar/small/<?php echo $post['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?php echo $post['login']; ?>"><?php echo $post['login']; ?></a> 
                        </span>
                        <span class="date"> 
                            <?php echo $post['post_date']; ?>
                        </span>
                        <?php if($post['post_comments'] !=0) { ?> 
                            <span class="otst"> | </span>
                            комментариев (<?php echo $post['post_comments'] ?>) 
                             
                        <?php } ?>
                    </div>  
                </div>
       
            <?php } ?>

        <?php } else { ?>

            <div class="no-content"><?= lanf('no-post'); ?>...</div>

        <?php } ?>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 