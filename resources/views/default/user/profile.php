<?php include TEMPLATE_DIR . '/header.php'; ?>
<style nonce="<?= $_SERVER['nonce']; ?>">
.profile-box {background:<?= $user['color']; ?>;}
</style>
<main class="w-75">
<div class="profile-box"> 
    <?php if($user['cover_art'] != 'cover_art.jpeg') { ?>
        <div class="profile-cover">
            <img class="cover-image" src="/uploads/users/cover/<?= $user['cover_art']; ?>" alt="<?= $user['login']; ?>">
        </div>
    <?php } else { ?>
        <div class="no-profile-cover"> </div>
    <?php } ?>
    <div class="profile-header">
        <?php if($uid['trust_level'] > 0) { ?>
            <?php if($uid['login'] != $user['login']) { ?> 
                <?php if($uid['trust_level'] >= Lori\Config::get(Lori\Config::PARAM_PM_MAIL)) { ?>
                    <a class="right pm" href="/u/<?= $user['login']; ?>/mess">
                        <i class="icon envelope"></i>
                    </a> 
                <?php } ?>
            <?php } else { ?>
                <a class="right pm"  href="/u/<?= $uid['login']; ?>/setting">
                    <i class="icon pencil"></i>
                </a> 
            <?php } ?>
        <?php } ?>
    </div>
</div>  
        
<div class="profile-box-telo">
    <div class="profile-ava">
        <img alt="<?= $user['login']; ?>" src="/uploads/users/avatars/<?= $user['avatar']; ?>">
    </div>
    <div class="profile-header-telo">
        <h1 class="profile">
            <?= $user['login']; ?> 
            <?php if($user['name']) { ?> / <?= $user['name']; ?><?php } ?>
        </h1>
        
 
    </div>

    <div class="stats box wide">
        <?php if($data['post_num_user'] != 0) { ?>
            <label class="required">Постов:</label>
            <span class="d">
                <a title="Всего постов <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/posts">
                    <?= $data['post_num_user']; ?>
                </a>
            </span>
            <br>
        <?php } ?>
        <?php if($data['answ_num_user'] != 0) { ?>
            <label class="required"><?= lang('Answers-m'); ?>:</label>
            <span class="d-cont">
                <a title="<?= lang('Answers-m'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/answers">
                    <?= $data['answ_num_user']; ?>
                </a>
            </span>
            <br>
        <?php } ?>
        <?php if($data['comm_num_user'] != 0) { ?>
            <label class="required">Комментариев:</label>
            <span class="d">
                <a title="Все комментарии <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/comments">
                    <?= $data['comm_num_user']; ?>
                </a>
            </span>
            <br>
        <?php } ?>
        
        <?php if($data['space_user']) { ?>
            <br>
            <label class="required"><?= lang('Created by'); ?>:</label>
            <br>
            <span class="d">
                <?php foreach ($data['space_user'] as  $space) { ?>
                    <div class="profile-space">
                        <img src="/uploads/spaces/logos/small/<?= $space['space_img']; ?>" alt="<?= $space['space_name']; ?>">
                        <a href="/s/<?= $space['space_slug'];?>"><?= $space['space_name'];?></a> 
                    </div>
                <?php } ?>
            </span>     
        <?php } ?>
    </div>
        
    <div class="box profile-telo">
    
        <div class="profile-about">
            <blockquote>
                <?php if($user['about']) { ?>
                <?= $user['about']; ?>
                <?php } else { ?>
                <?= lang('Riddle'); ?>...
                <?php } ?>
            </blockquote>
        </div>
        <div class="profile-about">
        <i class="icon calendar"></i>
        <span class="ts"><?= $user['created_at']; ?></span>  —  
            <?= $data['trust_level']['trust_name']; ?>
        </div>
        <?php if($user['my_post'] != 0) { ?>
            <h4><?= lang('Selected Post'); ?>:</h4>

            <div class="post-telo">
                <div class="post-body">
                    <a class="u-url" href="/post/<?= $onepost['post_id']; ?>/<?= $onepost['post_slug']; ?>">
                        <h2 class="title"><?= $onepost['post_title']; ?></h2>
                    </a>
                    
                    <a class="space-u" href="/s/<?= $onepost['space_slug']; ?>" title="<?= $onepost['space_name']; ?>">
                        <?= $onepost['space_name']; ?>
                    </a>
                    
                    <div class="post-footer"> 
                        <img class="ava" alt="<?= $user['login']; ?>" src="/uploads/users/avatars/small/<?= $user['avatar']; ?>">
                        <span class="user"> 
                            <a href="/u/<?= $user['login']; ?>">
                                <?= $user['login']; ?>
                            </a> 
                        </span>
   
                        <span class="date"> 
                           <?= $onepost['post_date'] ?>
                        </span>
                        <?php if($onepost['post_answers_num'] !=0) { ?> 
                            <span class="otst"> | </span>
                            <a class="u-url" href="/post/<?= $onepost['post_id']; ?>/<?= $onepost['post_slug']; ?>">
                              <?= lang('Answers-m'); ?> <?= $onepost['post_answers_num']; ?>  
                            </a>
                        <?php } ?>
                    </div>
                </div>                        
            </div>
        <?php } ?>
        
    </div>
</div>
</main>
<aside>
    <?php if ($uid['id'] == 0) { ?>
        <?php include TEMPLATE_DIR . '/_block/login.php'; ?>
    <?php } ?>    
</aside>
<?php include TEMPLATE_DIR . '/footer.php'; ?>