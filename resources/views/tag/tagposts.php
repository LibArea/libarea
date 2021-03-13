<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <h1><?php echo $data['title']; ?>:   <?php echo $data['tag']; ?></h1>

        <div class="telo comments">
            <?php if (!empty($data['posts'])) { ?>
         
                <?php foreach ($data['posts'] as  $post) { ?> 
 
                    <div id="vot<?php echo $post['post_id']; ?>" class="voters">
                        <div data-id="<?php echo $post['post_id']; ?>" class="post-up-id"></div>
                        <div class="score"><?php echo $post['post_votes']; ?></div>
                    </div>
                    <div class="post-telo">
                        <a class="u-url" href="/posts/<?php echo $post['post_slug']; ?>">
                            <h3 class="titl"><?php echo $post['post_title']; ?></h3>
                        </a>
          
                        <div class="footer">
                            <img class="ava" src="/images/user/small/<?php echo $post['avatar'] ?>">
                            <span class="user"> 
                                <a href="/u/<?php echo $post['login']; ?>"><?php echo $post['login']; ?></a> 
                            </span>
                            <span class="date"> 
                                <?php echo $post['date']; ?>
                            </span>
                            <?php if($post['post_comments'] !=0) { ?> 
                                <span class="otst"> | </span>
                                комментариев (<?php echo $post['post_comments']; ?>) 
                            <?php } ?>
                        </div>  
                    </div>
                <?php } ?>

            <?php } else { ?>

                <h3>Нет постов (в разработке)</h3>

                <p>К сожалению поcтов по данному тегу нет...</p>

            <?php } ?>
        </div> 
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>