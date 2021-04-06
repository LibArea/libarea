<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="telo">

    <?php if (!$uid['id']) { ?>
        <h1 class="top banner">Сайт в стадии разработке. Читать - <a href="/info/about">о нас</a>...</h1> 
    <?php } ?>  

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
                    <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                        <h2 class="titl"><?= $post['post_title']; ?></h2>
                        <?php if ($post['post_is_delete'] == 1) { ?> 
                            <svg class="md-icon delete">
                                <use xlink:href="/assets/svg/icons.svg#trash"></use>
                            </svg>
                        <?php } ?>
                        <?php if($post['post_closed'] == 1) { ?> 
                            <svg class="md-icon closed">
                                <use xlink:href="/assets/svg/icons.svg#lock"></use>
                            </svg>
                        <?php } ?>
                        <?php if($post['post_top'] == 1) { ?> 
                            <svg class="md-icon top">
                                <use xlink:href="/assets/svg/icons.svg#bulb-off"></use>
                            </svg>
                        <?php } ?>
                    </a>
                    <a class="space space_<?= $post['space_tip'] ?>" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                        <?= $post['space_name']; ?>
                    </a>
                    
                    <?php if($post['post_url']) { ?> 
                        <span class="post_url"> <?= $post['post_url']; ?></span> 
                    <?php } ?>
                    
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
                        <?php if($post['post_comments'] !=0) { ?> 
                            <span class="otst"> | </span>
                            <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                               <?= $post['post_comments']; ?>  <?= $post['num_comments']; ?>  
                            </a>
                        <?php } ?>
                    </div>
                </div>                        
            </div>
        <?php } ?>
        
    <?php } else { ?>

        <h3>Нет постов</h3>

        <p>К сожалению постов нет...</p>

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
    
</main>

<aside id="sidebar"> 
    <?php if($data['space_hide']) { ?>
        <div>
            <h3>Отписан</h3>  
            <?php foreach ($data['space_hide'] as  $hide) { ?>
                <a class="space space_<?= $hide['space_tip'] ?>" href="/s/<?= $hide['space_slug']; ?>" title="<?= $hide['space_name']; ?>">
                    <?= $hide['space_name']; ?>
                </a>
            <?php } ?>
            <div class="v-ots"></div> 
        </div>    
    <?php } ?>

    <?php foreach ($data['latest_comments'] as  $comm) { ?>
        <div class="sb-telo">
            <div class="sb-date"> 
                <img class="ava" alt="<?= $comm['login']; ?>" src="/uploads/avatar/small/<?= $comm['comment_avatar']; ?>">
                <?= $comm['comment_date']; ?>
            </div> 
            <a href="/posts/<?= $comm['post_slug']; ?>#comm_<?= $comm['comment_id']; ?>">
                <?= $comm['comment_content']; ?>...  
            </a>
       </div>
    <?php } ?>   
</aside> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>