<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">

        <a title="Участники" class="avatar-user right" href="/users">
            Участники
        </a>
        <h1><?php echo $data['title']; ?></h1>

        <div class="favorite">
            <?php if (!empty($data['favorite'])) { ?>
          
                <?php foreach ($data['favorite'] as $post) { ?> 
              
                    <div id="vot<?php echo $post['post_id']; ?>" class="voters">
                        <div data-id="<?php echo $post['post_id']; ?>" class="post-up-id"></div>
                        <div class="score"><?php echo $post['post_votes']; ?></div>
                    </div>
                
                    <div class="post-telo">
                        <a class="u-url" href="/posts/<?php echo $post['slug']; ?>">
                            <h3 class="titl"><?php echo $post['title']; ?></h3>
                        </a>
                        
                        <a class="space space_<?= $post['space_tip'] ?>" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                            <?= $post['space_name']; ?>
                        </a>
                        
                        <div class="footer">
                            <img class="ava" src="/uploads/avatar/small/<?php echo $post['avatar']; ?>">
                            <span class="user"> 
                                <a href="/u/<?php echo $post['login']; ?>"><?php echo $post['login']; ?></a> 
                            </span>
                            <span class="date"> 
                                <?php echo $post['date']; ?>
                            </span>
                            <?php if($post['post_comments'] !=0) { ?> 
                                <span class="otst"> | </span>
                                
                             <a href="/posts/<?php echo $post['slug']; ?>">    
                                комментариев (<?php echo $post['post_comments'] ?>) 
                            </a>     
                            <?php } ?>
                             <span class="otst"> | </span>
                            <span class="user-favorite" data-post="<?= $post['post_id']; ?>">
                                 <span class="mu_favorite">Убрать из избранного</span>
                            </span>   
                            
                        </div>  
                    </div>
           
                <?php } ?>

            <?php } else { ?>

                <p>К сожалению избранного нет...</p>
                <br>
            <?php } ?>
        </div> 
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 