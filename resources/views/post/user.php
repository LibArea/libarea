<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <a title="Участники" class="avatar-user right" href="/users">
            Участники
        </a>
        <h1 class="top"><?php echo $data['title']; ?></h1>

        <div class="telo posts">
            <?php if (!empty($data['posts'])) { ?>
          
                <?php foreach ($data['posts'] as $post) { ?> 
              
                    <div id="vot<?php echo $post['post_id']; ?>" class="voters">
                        <div data-id="<?php echo $post['post_id']; ?>" class="post-up-id"></div>
                        <div class="score"><?php echo $post['post_votes']; ?></div>
                    </div>
                
                    <div class="post-telo">
                        <a class="u-url" href="/posts/<?php echo $post['slug']; ?>">
                            <h3 class="titl"><?php echo $post['title']; ?></h3>
                        </a>
                        
                        <?php foreach ($post['tags'] as  $tag ): ?>                
                            <a class="tag tag_<?php echo $tag['tags_tip']; ?>" href="/t/<?php echo $tag['tags_slug']; ?>" title="<?php echo $tag['tags_name']; ?>">
                                <?php echo $tag['tags_name']; ?>
                            </a>
                        <?php endforeach; ?>
                        
                        <div class="footer">
                            <img class="ava" src="/images/user/small/<?php echo $post['avatar']; ?>">
                            <span class="user"> 
                                <a href="/u/<?php echo $post['login']; ?>"><?php echo $post['login']; ?></a> 
                            </span>
                            <span class="date"> 
                                <?php echo $post['date']; ?>
                            </span>
                            <?php if($post['post_comments'] !=0) { ?> 
                                <span class="otst"> | </span>
                                комментариев (<?php echo $post['post_comments'] ?>) 
                                 
                            <?php } ?>
                        </div>  
                    </div>
           
                <?php } ?>

            <?php } else { ?>

                <h3>Нет постов</h3>

                <p>К сожалению постов нет...</p>

            <?php } ?>
        </div> 
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 