<?php include TEMPLATE_DIR . '/header.php'; ?>
 
    <?php if (!$uid['id']) { ?>
        <div class="banner">
            <div class="banner-telo">
                <img width="28" height="28" src="/assets/svg/loriup.svg" alt="LoriUP">
                <h1 class="banner-h1"><?= lang('site-banner'); ?></h1> 
                <?= lang('site-banner-txt'); ?>. <a href="/info"><?= lang('Read'); ?></a>...
            </div>
        </div>    
    <?php } ?>  
    
<?php include TEMPLATE_DIR . '/_block/left-menu.php'; ?>
<main class="telo">

    <ul class="nav-tabs">
        <?php if($type == 'feed') { ?>
            <li class="active">
                <span><?= lang('Feed'); ?></span>
            </li>
            <li>
                <a href="/top">
                    <span>Top</span>
                </a>
            </li>
        <?php } else { ?>
            <li>  
                <a href="/">
                    <span><?= lang('Feed'); ?></span>
                </a>
            </li>    
            <li class="active">
                <span>Top</span>
            </li>
        <?php } ?>        
    </ul>

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
                    <div class="space-color space_<?= $post['space_color'] ?>"></div>
                    <a class="space-u" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                        <?= $post['space_name']; ?>
                    </a>
                    
                    <?php if($post['post_url']) { ?> 
                        <a class="post_url" href="/domain/<?= $post['post_url']; ?>"><?= $post['post_url']; ?></a> 
                    <?php } ?>
                    
                    <div class="show_add_<?= $post['post_id']; ?>">
                        <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
                            <span>&#9658;</span> 
                            <?= $post['post_content_preview']; ?>
                            <span class="s_<?= $post['post_id']; ?> show_detail">... </span>
                        </div>
                    </div>
                    
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
                            <a class="u-url" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                <?php if($post['post_type'] ==0) { ?>
                                    <?= $post['post_answers_num'] + $post['post_comments_num']; ?> коммент...
                                <?php } else { ?>      
                                    <?= $post['post_answers_num']; ?>  <?= $post['lang_num_answers']; ?>   
                                <?php } ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>                        
            </div>
        <?php } ?>
        
    <?php } else { ?>
        <div class="no-content"><?= lang('no-post'); ?>...</div>
    <?php } ?>
    
   <?php if(!($data['pNum'] > $data['pagesCount'])) { ?>
        <div class="pagination">   
            <?php if($data['pNum'] != 1) { ?> 
                <a href="/<?= $data['pNum'] - 1; ?>"> << <?= lang('Page'); ?> <?= $data['pNum'] - 1; ?></a> 
            <?php } ?>
            <?php if($data['pagesCount'] != $data['pNum'] && $data['pNum'] != 1) { ?>|<?php } ?> 
            <?php if($data['pagesCount'] > $data['pNum']) { ?>
                <a href="/<?= $data['pNum'] + 1; ?>"><?= lang('Page'); ?>  <?= $data['pNum'] + 1; ?> >></a> 
            <?php } ?>
        </div>
    <?php } ?>
 
</main>

<aside class="sidebar"> 
    <?php if(!$space_bar) { ?>
        <div class="space-no-user"> 
            <i class="icon diamond"></i>
            Читайте больше! <br><a href="/space">Подпишитесь</a> на пространства, которые вам интересны.
        </div>
    <?php }  ?>    

    <?php foreach ($data['latest_answers'] as  $answ) { ?>
        <div class="sb-telo comm-space-color-<?= $answ['space_color']; ?>">
            <div class="sb-date"> 
                <img class="ava" alt="<?= $answ['login']; ?>" src="/uploads/avatar/small/<?= $answ['avatar']; ?>">
                <?= $answ['answer_date']; ?>
            </div> 
            <a href="/post/<?= $answ['post_id']; ?>/<?= $answ['post_slug']; ?>#answ_<?= $answ['answer_id']; ?>">
                <?= $answ['answer_content']; ?>...  
            </a>
       </div>
    <?php } ?>  
</aside> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>