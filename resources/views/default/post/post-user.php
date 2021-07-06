<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <div class="telo posts">
                    <?php if (!empty($posts)) { ?>
                  
                        <?php foreach ($posts as $post) { ?> 

                            <div class="post-header small">
                                <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
                                <span class="indent"></span>

                                <a class="date" href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 

                                <span class="indent"></span>
                                <?= $post['post_date']; ?>

                                <span class="indent"> &#183; </span> 
                                <a href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
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
                                            <a rel="nofollow" href="/login"><div class="up-id"></div></a>
                                            <div class="score"><?= $post['post_votes']; ?></div>
                                        </div>
                                    <?php } else { ?> 
                                        <?php if ($post['votes_post_user_id'] || $uid['id'] == $post['post_user_id']) { ?>
                                            <div class="voters active">
                                                <div class="up-id"></div>
                                                <div class="score"><?= $post['post_votes']; ?></div>
                                            </div>
                                        <?php } else { ?>
                                            <div id="up<?= $post['post_id']; ?>" class="voters">
                                                <div data-id="<?= $post['post_id']; ?>" data-type="post" class="up-id"></div>
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

                        <div class="no-content"><i class="icon info"></i> <?= lang('no-post'); ?>...</div>

                    <?php } ?>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 