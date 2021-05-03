<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <h1><?= $data['h1']; ?></h1>

   
    <?php if (!empty($posts)) { ?> 
  
        <?php foreach ($posts as  $post) { ?>
        
            <div class="post-telo">
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
                    <a class="u-url" href="/posts/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                        <h2 class="titl"><?= $post['post_title']; ?></h2>
                        <?php if ($post['post_is_delete'] == 1) { ?> 
                            <icon name="trash"></icon>
                        <?php } ?>
                        <?php if($post['post_closed'] == 1) { ?> 
                            <icon name="lock"></icon>
                        <?php } ?>
                        <?php if($post['post_top'] == 1) { ?> 
                            <icon name="pin"></icon>
                        <?php } ?>
                    </a>
                    <div class="space-color space_<?= $post['space_color'] ?>"></div>
                    <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                        <?= $post['space_name']; ?>
                    </a>
                    
                    <?php if ($post['post_content_preview']) { ?>
                        <div class="show">
                            <span class="show_add_<?= $post['post_id']; ?>">
                                <a data-post_id="<?= $post['post_id']; ?>" class="showpost">
                                    <span>&#9658;</span> 
                                    <?= $post['post_content_preview']; ?>... 
                                </a>
                            </span>
                        </div>
                    <?php } ?>
                    <div id="show_<?= $post['post_id']; ?>" class="show_detail"></div> 
                    
                    <div class="footer">
                        <img class="ava" alt="<?= $post['login']; ?>" src="/uploads/avatar/small/<?= $post['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>">
                                <?= $post['login']; ?>
                            </a> 
                        </span>
                        <span class="date"> 
                           <?= $post['post_date'] ?>
                        </span>
                        <?php if($post['post_answers_num'] !=0) { ?> 
                            <span class="otst"> | </span>
                            <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                               <?= $post['post_answers_num']; ?>  <?= $post['lang_num_answers']; ?>  
                            </a>
                        <?php } ?>
                    </div>
                </div>                        
            </div>
        <?php } ?>
        
    <?php } else { ?>
        <div class="no-content"><?= lang('no-post'); ?>...</div>
    <?php } ?>
   
   
 
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 