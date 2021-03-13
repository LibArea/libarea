<?php include TEMPLATE_DIR . '/header.php'; ?>
    <section>
        <div class="wrap">

         <div class="right"><center> <br> <img src="/svg/logo.svg" width="100" height="100" class="hl-block" alt="HL">
            <br><small> <span class="mlogout"> Микрофреймворк HLEB</span>
            <br><a href="/admin">админка (открыта)</a></small></center> </div>
        
            <?php if (!$usr['id'] > 0) { ?>
                <h1 class="top banner">Сайт в стадии разработке. Читать - <a href="/info/about">о нас</a>...</h1> 
            <?php } ?>    
        
            <div class="telo">
            <?php if (!empty($data['posts'])) { ?>
          
                <?php foreach ($data['posts'] as  $post) { ?>
                    <div class="post-telo">
                    
                        <div id="vot<?= $post['post_id']; ?>" class="voters">
                            <div data-id="<?= $post['post_id']; ?>" class="post-up-id"></div>
                            <div class="score"><?= $post['post_votes']; ?></div>
                        </div>
                    
                        <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                            <h3 class="titl"><?= $post['post_title']; ?></h3>
                        </a>
                        
                        <?php foreach ($post['tags'] as  $tag) { ?>                
                            <a class="tag tag_<?= $tag['tags_tip'] ?>" href="/t/<?= $tag['tags_slug']; ?>" title="<?= $tag['tags_name']; ?>">
                                <?= $tag['tags_name']; ?>
                            </a>
                        <?php } ?>
                        
                        <div class="footer">
                            <img class="ava" src="/images/user/small/<?= $post['avatar']; ?>">
                            <span class="user"> 
                                <a href="/u/<?= $post['login']; ?>">
                                    <?= $post['login']; ?>
                                </a> 
                            </span>
                            <span class="date"> 
                               <?= $post['date'] ?>
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
                
                <div class="pagination">
                    <?php for ($pageNum = 1; $pageNum <= $data['pagesCount']; $pageNum++) { ?>
                        <?php if ($data['pNum'] == $pageNum || !$data['pNum'] == 1) { ?>
                            <span class="page"><?= $pageNum; ?></span>
                        <?php } else { ?>
                            <a href="/<?= $pageNum == 1 ? '' : $pageNum ?>"><?= $pageNum; ?></a>
                        <?php } ?>
                    <?php } ?>
                </div>
                
            <?php } else { ?>

                <h3>Нет постов</h3>

                <p>К сожалению постов нет...</p>

            <?php } ?>
            </div>
        
            
            
        </div>
    <section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
