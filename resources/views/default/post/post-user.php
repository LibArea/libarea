<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1 class="top"><?= $data['h1']; ?></h1>

                <div class="telo posts">
                    <?php if (!empty($posts)) { ?>
                  
                        <?php foreach ($posts as $post) { ?> 

                            <div class="post-header small">
                                <img class="ava" src="<?= user_avatar_url($post['avatar'], 'small'); ?>">
                                <span class="otst"></span>
                                <span class="user"> 
                                    <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                                </span>
                                <span> 
                                    <?= $post['post_date']; ?>
                                </span>
                                <span class="otst"> &#183; </span> 
                                <a class="u-url" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                                    <?= $post['space_name']; ?>
                                </a>
                            </div> 
                            <div class="post-telo">
                            
                                <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                    <h3 class="title"><?= $post['post_title']; ?></h3>
                                </a>
                                
                                <div class="post-footer date">
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
                                    <?php if($post['post_answers_num'] !=0) { ?>
                                        <span class="right">
                                            <i class="icon bubbles"></i> <?= $post['post_answers_num'] ?>
                                        </span>
                                    <?php } ?>
                                </div>  
                            </div>
                   
                        <?php } ?>

                    <?php } else { ?>

                        <div class="no-content"><?= lang('no-post'); ?>...</div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <?php if ($uid['id'] == 0) { ?>
            <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
        <?php } else { ?>
            <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
        <?php } ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 