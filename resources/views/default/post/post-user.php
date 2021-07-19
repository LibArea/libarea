<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/', lang('Home'), '/u/' . Request::get('login'), lang('Profile'), $data['h1']); ?>

                <div class="telo posts">
                    <?php if (!empty($posts)) { ?>
                  
                        <?php foreach ($posts as $post) { ?> 

                            <div class="post-header small">
                                <a class="gray" href="/u/<?= $post['login']; ?>">
                                    <?= user_avatar_img($post['avatar'], 'small', $post['login'], 'ava'); ?>
                                    <span class="indent"></span>
                                    <?= $post['login']; ?>
                                </a> 
                                <span class="indent gray lowercase">
                                    <?= $post['post_date']; ?>
                                </span>
                                <a class="gray indent" href="/s/<?= $post['space_slug']; ?>" title="<?= $post['space_name']; ?>">
                                    <?= $post['space_name']; ?>
                                </a>
                            </div> 
                            <div class="post-telo">
                            
                                <a href="/post/<?= $post['post_id']; ?>/<?= $post['post_slug']; ?>">
                                    <h3 class="title"><?= $post['post_title']; ?></h3>
                                </a>
                                
                                <div class="post-footer gray">
                                    <?= votes($uid['id'], $post, 'post'); ?>
                                    
                                    <?php if($post['post_answers_count'] !=0) { ?>
                                        <span class="right">
                                            <i class="icon bubbles"></i> <?= $post['post_answers_count'] ?>
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