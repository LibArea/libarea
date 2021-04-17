<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <h1><?= $data['h1']; ?></h1>

    <div class="favorite">
        <?php if (!empty($favorite)) { ?>
      
            <?php foreach ($favorite as $post) { ?> 
          
                <div id="vot<?= $post['post_id']; ?>" class="voters">
                    <div data-id="<?= $post['post_id']; ?>" class="post-up-id"></div>
                    <div class="score"><?= $post['post_votes']; ?></div>
                </div>
            
                <div class="post-telo">
                    <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                        <h3 class="titl"><?= $post['post_title']; ?></h3>
                    </a>
                    
                    <div class="space-color space_<?= $post['space_color'] ?>"></div>
                    <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                        <?= $post['space_name']; ?>
                    </a>
                    
                    <div class="footer">
                        <img class="ava" src="/uploads/avatar/small/<?= $post['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                        </span>
                        <span class="date"> 
                            <?= $post['date']; ?>
                        </span>
                        <?php if($post['post_comments'] !=0) { ?> 
                            <span class="otst"> | </span>
                            
                         <a href="/posts/<?= $post['post_slug']; ?>">    
                            комментариев (<?= $post['post_comments'] ?>) 
                        </a>     
                        <?php } ?>
                        
                        <?php if($uid['id'] > 0) { ?>
                            <?php if($uid['id'] == $post['favorite_uid']) { ?>
                                <span class="otst"> | </span>
                                <span class="user-favorite" data-post="<?= $post['post_id']; ?>">
                                     <span class="mu_favorite">Убрать из избранного</span>
                                </span>  
                            <?php } ?>                                
                        <?php } ?>
                    </div>  
                </div>
       
            <?php } ?>

        <?php } else { ?>

            <p>К сожалению избранного нет...</p>
            <br>
        <?php } ?>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 