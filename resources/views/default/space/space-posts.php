<?php include TEMPLATE_DIR . '/header.php'; ?>
</div>
<div class="space-box space_<?= $space_info['space_id']; ?>">
    <div class="wrap">
         <?php if(!$uid['id']) { ?> 
                <div class="right"> 
                    <a href="/login"><div class="hide-space-id yes-space">+ <?= lang('Read'); ?></div></a>
                </div>
        <?php } else { ?>
            <?php if($space_info['space_id'] !=1) { ?>
                <?php if($space_info['space_user_id'] != $uid['id']) { ?>
                    <div class="right"> 
                        <?php if($space_signed == 1) { ?>
                            <div data-id="<?= $space_info['space_id']; ?>" class="hide-space-id no-space">
                                <?= lang('Unsubscribe'); ?>
                            </div>
                        <?php } else { ?> 
                            <div data-id="<?= $space_info['space_id']; ?>" class="hide-space-id yes-space">
                                + <?= lang('Read'); ?>
                            </div>
                        <?php } ?>   
                    </div>
                <?php } ?>
            <?php } ?>                 
        <?php } ?> 
        <div class="space-text">
            <img class="space-box-img" src="/uploads/space/<?= $space_info['space_img']; ?>">
            <a title="<?= $space_info['space_name']; ?>" href="/s/<?= $space_info['space_slug']; ?>">
                <h1><?= $space_info['space_name']; ?></h1>
            </a>  
            <div class="data">
            <?= lang('Created by'); ?>: <a href="/u/<?= $space_info['login']; ?>"><?= $space_info['login']; ?></a>
            
            — <?= $space_info['space_date']; ?>
            
            </div> 
        </div>
    </div>    
</div>
<div class="wrap">

<?php include TEMPLATE_DIR . '/_block/left-menu.php'; ?>
<?php if ($space_info['space_is_delete'] == 0) { ?>
<main>
    <ul class="nav-tabs">
        <?php if($type == 'feed') { ?>
            <li class="active">
               <span><?= lang('Feed'); ?></span>
            </li>
            <li>
                <a href="/s/<?= $space_info['space_slug']; ?>/top">
                    <span>Top</span>
                </a>
            </li>
        <?php } else { ?>
            <li>  
                <a href="/s/<?= $space_info['space_slug']; ?>">
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
                        <h3 class="titl"><?= $post['post_title']; ?></h3>
                    </a>
                    
                    <?php if($post['st_id']) { ?>
                        <a class="space-u tag-u" href="/s/<?= $space_info['space_slug']; ?>/<?= $post['st_id']; ?>" title="<?= $post['st_title']; ?>"><?= $post['st_title']; ?></a>
                    <?php } ?>
                     
                    <div class="show">
                        <span class="show_add_<?= $post['post_id']; ?>">
                            <div data-post_id="<?= $post['post_id']; ?>" class="showpost">
                                <span>&#9658;</span> 
                                <?= $post['post_content_preview']; ?>
                                <span class="s_<?= $post['post_id']; ?> show_detail">... </span>
                            </div>
                        </span>
                    </div>

                <div id="show_<?= $post['post_id']; ?>" class="show_detail"></div> 
                    <div class="footer">
                        <img class="ava" src="/uploads/avatar/small/<?= $post['avatar'] ?>">
                        <span class="user"> 
                            <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                        </span>
                        <span class="date">
                            <?= $post['post_date']; ?>
                        </span>
                        <?php if($post['post_answers_num'] !=0) { ?> 
                            <span class="otst"> | </span>
                            <a class="u-url" href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                <?= $post['post_answers_num']; ?>  <?= $post['lang_num_answers']; ?>
                            </a>                                
                        <?php } ?>
                    </div>  
                </div>
            </div>    
        <?php } ?>

        <?php } else { ?>
            <div class="no-content"><?= lang('no-post'); ?>...</div>
        <?php } ?>
    </main>

    <aside class="sidebar">
        <?php if($space_info['space_id'] != 1) { ?>
            Читают <?= $space_info['users']; ?>
        <?php } ?>
    
        <?php if($uid['trust_level'] == 5 || $space_info['space_user_id'] == $uid['id']) { ?>
            <div class="edit-space right">
                <a class="edit-space" href="/space/<?= $space_info['space_slug']; ?>/edit">
                    <?= lang('Edit'); ?>
                </a>
            </div> 
        <?php } ?>
            
        <div class="space-text-sb">
            <div class="space-text-bar"> 
                <?= $space_info['space_text']; ?>
            </div>
        </div>
      
        <?php if (!empty($tags)) { ?>
            <div class="space-tags">
                <div class="menu-m"><?= lang('Tags'); ?></div>
                <?php foreach ($tags as  $tag) { ?>  
                    <a <?php if ($uid['uri'] == '/s/'.$tag['space_slug'] .'/'.$tag['st_id']) { ?> class="avtive" <?php } ?> href="/s/<?= $space_info['space_slug']; ?>/<?= $tag['st_id']; ?>">
                        <?= $tag['st_title']; ?>
                    <a>
                <?php } ?>
            </div>
        <?php } ?> 
        <br>
    </aside> 
<?php } else { ?>
    <main>
        <div class="no-content red"><?= lang('ban-space'); ?>...</div>
    </main>
<?php } ?> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>