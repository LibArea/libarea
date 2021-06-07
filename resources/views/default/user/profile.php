<?php include TEMPLATE_DIR . '/header.php'; ?>

<?php if($user['cover_art'] != 'cover_art.jpeg') { ?>
    <style nonce="<?= $_SERVER['nonce']; ?>">
    .profile-box-cover {background-image: url(<?= user_cover_url($user['cover_art']); ?>); background-position: 50% 50%;min-height: 310px;}
    </style>
    <div class="profile-box-cover">
    <div class="wrap">      
<?php } else { ?>
    <style nonce="<?= $_SERVER['nonce']; ?>">
    .profile-box {background:<?= $user['color']; ?>;min-height: 90px;}
    </style>
    <div class="profile-box">
    <div class="wrap">    
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
        <div class="profile-ava">
            <img alt="<?= $user['login']; ?>" src="<?= user_avatar_url($user['avatar'], 'max'); ?>">
        </div>
        
    </div>
    </div> 
 
<div class="wrap">
<main class="w-75">     
<div class="profile-box-telo">
     
    <div class="profile-header-telo">
        <h1 class="profile">
            <?= $user['login']; ?> 
            <?php if($user['name']) { ?> / <?= $user['name']; ?><?php } ?>
        </h1>
    </div>

    <div class="stats<?php if($user['cover_art'] == 'cover_art.jpeg') { ?> no-cover<?php } ?>">
        <?php if($data['post_num_user'] != 0) { ?>
            <label class="required"><?= lang('Posts-m'); ?>:</label>
            <span class="d">
                <a title="<?= lang('Posts-m'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/posts">
                    <?= $data['post_num_user']; ?>
                </a>
            </span>
            <br>
        <?php } ?>
        <?php if($data['answ_num_user'] != 0) { ?>
            <label class="required"><?= lang('Answers'); ?>:</label>
            <span class="d-cont">
                <a title="<?= lang('Answers'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/answers">
                    <?= $data['answ_num_user']; ?>
                </a>
            </span>
            <br>
        <?php } ?>
        <?php if($data['comm_num_user'] != 0) { ?>
            <label class="required"><?= lang('Comments'); ?>:</label>
            <span class="d">
                <a title="<?= lang('Comments'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/comments">
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
                        <img src="<?= spase_logo_url($space['space_img'], 'small'); ?>" alt="<?= $space['space_name']; ?>">
                        <a href="/s/<?= $space['space_slug'];?>"><?= $space['space_name'];?></a> 
                    </div>
                <?php } ?>
            </span>     
        <?php } ?>
    </div>
        
    <div class="box profile-telo">
    
        <div class="profile-about">
            <blockquote>
                <?= $user['about']; ?>...
            </blockquote>
        </div>
    
        <div class="profile-about">
            <i class="icon calendar"></i>
            <span class="ts"><?= $user['created_at']; ?></span>  —  
            <?= $data['trust_level']['trust_name']; ?>
        </div>

        <h2><?= lang('Contacts'); ?></h2>
        <?php if($user['website']) { ?>    
            <div class="boxline">
                <label for="name"><?= lang('URL'); ?></label>
                <a href="<?= $user['website']; ?>" rel="noopener nofollow ugc">
                    <?= $user['website']; ?>
                </a>
            </div>
        <?php } ?>
        <?php if($user['location']) { ?> 
            <div class="boxline">
                <label for="name"><?= lang('City'); ?></label>
                <?= $user['location']; ?>
            </div>
        <?php } ?>
        <?php if($user['public_email']) { ?> 
            <div class="boxline">
                <label for="name"><?= lang('E-mail'); ?></label>
                <a href="mailto:<?= $user['public_email']; ?>" rel="noopener nofollow ugc">
                    <?= $user['public_email']; ?>
                </a>
            </div>
        <?php } ?>
        <?php if($user['skype']) { ?>
            <div class="boxline">
                <label for="name"><?= lang('Skype'); ?></label>
                <a href="skype:<?= $user['skype']; ?>" rel="noopener nofollow ugc">
                    <?= $user['skype']; ?>
                </a>
            </div>
        <?php } ?>
        <?php if($user['twitter']) { ?>
            <div class="boxline">
                <label for="name"><?= lang('Twitter'); ?></label>
                <a href="https://twitter.com/<?= $user['twitter']; ?>" rel="noopener nofollow ugc">
                    <?= $user['twitter']; ?>
                </a>
            </div>
        <?php } ?>
        <?php if($user['telegram']) { ?>
            <div class="boxline">
                <label for="name"><?= lang('Telegram'); ?></label>
                <a href="tg://resolve?domain=<?= $user['telegram']; ?>" rel="noopener nofollow ugc">
                    <?= $user['telegram']; ?>
                </a>
            </div>
        <?php } ?>
        <?php if($user['vk']) { ?>
            <div class="boxline">
                <label for="name"><?= lang('VK'); ?></label>
                <a href="https://vk.com/<?= $user['vk']; ?>" rel="noopener nofollow ugc">
                    <?= $user['vk']; ?>
                </a>
            </div> 
        <?php } ?>    

        <?php if($user['my_post'] != 0) { ?>
            <br>
            <h3><?= lang('Selected Post'); ?></h3>

            <div class="post-telo">
                <div class="post-body">
                    <a class="u-url" href="/post/<?= $onepost['post_id']; ?>/<?= $onepost['post_slug']; ?>">
                        <h2 class="title"><?= $onepost['post_title']; ?></h2>
                    </a>

                    <div class="post-footer lowercase"> 
                        <img class="ava" alt="<?= $user['login']; ?>" src="<?= user_avatar_url($user['avatar'], 'small'); ?>">
                        <span class="user"> 
                            <a href="/u/<?= $user['login']; ?>">
                                <?= $user['login']; ?>
                            </a> 
                        </span>
                        <span class="date"> 
                           <?= $onepost['post_date'] ?>
                        </span>
                        <span class="otst"> &#183; </span> 
                        <a class="u-url" href="/s/<?= $onepost['space_slug']; ?>" title="<?= $onepost['space_name']; ?>">
                            <?= $onepost['space_name']; ?>
                        </a> 
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
        <h3 class="badge"><?= lang('Badges'); ?></h3>
        <div class="profile-badge">
            <?php if($user['id'] < 50) { ?>
               <i title="<?= lang('Joined in the early days'); ?>" class="icon badge"></i>
            <?php } ?>
        </div>
        <br>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>