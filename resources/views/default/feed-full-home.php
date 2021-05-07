<?php foreach ($posts as  $post) { ?>

    <div class="post-telo full">
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
            <a class="u-url" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                <h2 class="titl"><?= $post['post_title']; ?></h2>
                <?php if ($post['post_is_delete'] == 1) { ?> 
                    <i class="icon trash"></i>
                <?php } ?>
                <?php if($post['post_closed'] == 1) { ?> 
                    <i class="icon lock"></i>
                <?php } ?>
                <?php if($post['post_top'] == 1) { ?> 
                    <i class="icon pin"></i>
                <?php } ?>
                <?php if($post['post_lo'] > 0) { ?> 
                    <i class="icon trophy lo"></i>
                <?php } ?>
                <?php if($post['post_type'] == 1) { ?> 
                    <i class="icon question qa"></i>
                <?php } ?>
            </a>
            
            <div class="fuul-head">
                <img class="ava" alt="<?= $post['login']; ?>" src="/uploads/avatar/small/<?= $post['avatar']; ?>">
                <span class="user"> 
                    <a href="/u/<?= $post['login']; ?>">
                        <?= $post['login']; ?>
                    </a> 
                </span>
                &nbsp; 
                <div class="space-color space_<?= $post['space_color'] ?>"></div>
                <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                    <?= $post['space_name']; ?>
                </a>
                &nbsp;
                <span class="date"> 
                   <?= $post['post_date'] ?>
                </span>
                &nbsp;
                <?php if($post['post_url']) { ?> 
                    <a class="post_url" href="/domain/<?= $post['post_url']; ?>"><?= $post['post_url']; ?></a> 
                <?php } ?>
            </div>
            
            
            <div class="full-fuul-home">
            
                <?php if($post['post_thumb_img']) { ?> 
                    <img class="thumb" alt="<?= $post['post_url']; ?>" src="/uploads/thumbnails/<?= $post['post_thumb_img']; ?>">
                <?php } ?>
                
                <?= Base::cutWords($post['post_content'], 248); ?> 
  
            </div>

        </div>                        
    </div> 
<?php } ?>