<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">
        <div class="right">
            <div class="space-bg no-mob">
                <?= $data['posts'][0]['space_description']; ?>
            </div>
             
            <?php if(!$uid['id']) { ?> 
                <div> 
                <br>
                    <a href="/login"><div class="hide-space-id add-space">Подписаться</div></a>
                </div>
            <?php } else { ?>
                <div> 
                <br>  
                    <?php if($data['space_hide'] == 1) { ?> 
                        <div data-id="<?= $data['posts'][0]['space_id']; ?>" class="hide-space-id add-space">Подписаться</div>
                    <?php } else { ?> 
                        <div data-id="<?= $data['posts'][0]['space_id']; ?>" class="hide-space-id no-space">Отписаться</div>
                    <?php } ?>   
                </div>  
            <?php } ?>    
                        
        </div>
        
        <h1><?= $data['title']; ?>: <?= $data['space']; ?></h1>
 
        <div class="telo comments">
            <?php if (!empty($data['posts'])) { ?>
         
                <?php foreach ($data['posts'] as  $post) { ?> 
 
                    <div id="vot<?= $post['post_id']; ?>" class="voters">
                        <div data-id="<?= $post['post_id']; ?>" class="post-up-id"></div>
                        <div class="score"><?= $post['post_votes']; ?></div>
                    </div>
                    <div class="post-telo">
                        <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                            <h3 class="titl"><?= $post['post_title']; ?></h3>
                        </a>
          
                        <div class="footer">
                            <img class="ava" src="/images/user/small/<?= $post['avatar'] ?>">
                            <span class="user"> 
                                <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                            </span>
                            <span class="date"> 
                                <?= $post['date']; ?>
                            </span>
                            <?php if($post['num_comments'] !=0) { ?> 
                                <span class="otst"> | </span>
                                <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                                    <?= $post['num_comments']; ?>  <?= $post['post_comments']; ?>
                                </a>                                
                            <?php } ?>
                        </div>  
                    </div>
                <?php } ?>

            <?php } else { ?>

                <h3>Нет постов (в разработке)</h3>

                <p>К сожалению поcтов по данному пространству нет...</p>

            <?php } ?>
        </div> 
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>