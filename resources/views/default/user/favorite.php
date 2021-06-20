<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><?= $data['h1']; ?></h1>

                <div class="favorite max-width">
                    <?php if (!empty($favorite)) { ?>
                  
                        <?php $counter = 0; foreach ($favorite as $fav) { $counter++; ?> 
                        
                            <?php if ($fav['favorite_type'] == 1) { ?> 
                                <div class="voters-fav">
                                   <div class="score"><?= $counter; ?>.</div> 
                                </div>
                                <div class="post-telo">
                                    <a href="/post/<?= $fav['post_id']; ?>/<?= $fav['post_slug']; ?>">
                                        <h3 class="title"><?= $fav['post_title']; ?></h3>
                                    </a>

                                    <div class="lowercase small">
                                        <img class="ava" src="<?= user_avatar_url($fav['avatar'], 'small'); ?>">
                                        <a class="date"  href="/u/<?= $fav['login']; ?>"><?= $fav['login']; ?></a> 

                                        <span class="date"><?= $fav['date']; ?></span>
                                        
                                        <span class="indent"> &#183; </span> 
                                        <a class="date" href="/s/<?= $fav['space_slug']; ?>" title="<?= $fav['space_name']; ?>">
                                            <?= $fav['space_name']; ?>
                                        </a> 
                                        <?php if($fav['post_answers_num'] !=0) { ?> 
                                            <span class="indent"></span>
                                            <a class="date" href="/post/<?= $fav['post_id']; ?>/<?= $fav['post_slug']; ?>">    
                                                <i class="icon bubbles"></i>  <?= $fav['post_answers_num'] ?> 
                                            </a>     
                                        <?php } ?>
                                        <?php if($uid['id'] > 0) { ?>
                                            <?php if($uid['id'] == $fav['favorite_uid']) { ?>
                                                <span class="indent"> &#183; </span> 
                                                <span class="user-post-fav right" data-post="<?= $fav['post_id']; ?>">
                                                     <span class="mu_favorite date"><?= lang('Remove'); ?></span>
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
                                    <a href="/post/<?= $fav['post']['post_id']; ?>/<?= $fav['post']['post_slug']; ?>#answ_<?= $fav['answer_id']; ?>">
                                       <h3 class="title"><?= $fav['post']['post_title']; ?></h3>
                                    </a>
                                    <div class="space-color space_<?= $fav['post']['space_color'] ?>"></div>
                                    <a class="date" href="/s/<?= $fav['post']['space_slug']; ?>" title="<?= $fav['post']['space_name']; ?>">
                                        <?= $fav['post']['space_name']; ?>
                                    </a>
                                    <?php if($uid['id'] > 0) { ?>
                                        <?php if($uid['id'] == $fav['favorite_uid']) { ?>
                                            <span class="user-answ-fav right" data-answ="<?= $fav['answer_id']; ?>">
                                                 <span class="favcomm date"><?= lang('Remove'); ?></span>
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
                        <p class="no-answer"><i class="icon lock"></i> <?= lang('There are no favorites'); ?>...</p>
                    <?php } ?>
                    <br>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('Under development'); ?>...
            </div>
        </div>   
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 