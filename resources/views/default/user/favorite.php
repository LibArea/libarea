<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100 max-width">
    <h1><?= $data['h1']; ?></h1>

    <div class="favorite max-width">
        <?php if (!empty($favorite)) { ?>
      
            <?php $counter = 0; foreach ($favorite as $fav) { $counter++; ?> 
            
                <?php if ($fav['favorite_type'] == 1) { ?> 
                    <div class="voters-fav">
                       <div class="score"><?= $counter; ?>.</div> 
                    </div>
                    <div class="post-telo">
                        <a class="u-url" href="/posts/<?= $fav['post_slug']; ?>">
                            <h3 class="titl"><?= $fav['post_title']; ?></h3>
                        </a>
                        
                        <div class="space-color space_<?= $fav['space_color'] ?>"></div>
                        <a class="space-u" href="/s/<?= $fav['space_slug']; ?>" title="<?= $fav['space_name']; ?>">
                            <?= $fav['space_name']; ?>
                        </a>
                        <div class="footer">
                            <img class="ava" src="/uploads/avatar/small/<?= $fav['avatar']; ?>">
                            <span class="user"> 
                                <a href="/u/<?= $fav['login']; ?>"><?= $fav['login']; ?></a> 
                            </span>
                            <span class="date"> 
                                <?= $fav['date']; ?>
                            </span>
                            <?php if($fav['post_answers'] !=0) { ?> 
                                <span class="otst"> | </span>
                                
                             <a href="/posts/<?= $fav['post_slug']; ?>">    
                                комментариев (<?= $fav['post_answers'] ?>) 
                            </a>     
                            <?php } ?>
                            <?php if($uid['id'] > 0) { ?>
                                <?php if($uid['id'] == $fav['favorite_uid']) { ?>
                                    <span class="user-post-fav right" data-post="<?= $fav['post_id']; ?>">
                                         <span class="mu_favorite">убрать</span>
                                    </span>  
                                <?php } ?>                                
                            <?php } ?>
                        </div>  
                    </div>
                <?php } ?>
                <?php if ($fav['favorite_type'] == 2) { ?> 
                    <div class="voters-fav">
                       <div class="score"><?= $counter; ?>.</div> 
                    </div>
                    <div class="post-telo fav-answ">
                        <a class="u-url"  href="/posts/<?= $fav['post']['post_slug']; ?>#answ_<?= $fav['answer_id']; ?>">
                           <h3 class="titl"><?= $fav['post']['post_title']; ?></h3>
                        </a>
                        <div class="space-color space_<?= $fav['post']['space_color'] ?>"></div>
                        <a class="space-u" href="/s/<?= $fav['post']['space_slug']; ?>" title="<?= $fav['post']['space_name']; ?>">
                            <?= $fav['post']['space_name']; ?>
                        </a>
                                                <?php if($uid['id'] > 0) { ?>
                            <?php if($uid['id'] == $fav['favorite_uid']) { ?>
                                <span class="user-answ-fav right" data-answ="<?= $fav['answer_id']; ?>">
                                     <span class="favcomm">убрать</span>
                                </span>  
                            <?php } ?>                                
                        <?php } ?>
                        <div class="telo-fav-answ">
                            <?= $fav['answer_content']; ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>

        <?php } else { ?>

            <p>К сожалению избранного нет...</p>
            <br>
        <?php } ?>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 