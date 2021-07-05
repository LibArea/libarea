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
        <?php if($uid['id'] > 0) { ?>
            <div class="profile-header">
                <?php if($uid['login'] != $user['login']) { ?>
                    <?php if($button_pm === true) { ?>            
                        <a class="right pm" href="/u/<?= $user['login']; ?>/mess">
                            <i class="icon envelope"></i>
                        </a>
                    <?php } ?>    
                <?php } else { ?>
                    <a class="right pm"  href="/u/<?= $uid['login']; ?>/setting">
                        <i class="icon pencil"></i>
                    </a> 
                <?php } ?>
            </div>
        <?php } ?>    
        <div class="profile-ava">
            <img alt="<?= $user['login']; ?>" src="<?= user_avatar_url($user['avatar'], 'max'); ?>">
        </div>
    </div>
    </div> 
 
<div class="wrap">
<main>     
<div class="profile-box-telo white-box">
    <div class="inner-padding">
     
    <div class="profile-header-telo">
        <h1 class="profile">
            <?= $user['login']; ?> 
            <?php if($user['name']) { ?> / <?= $user['name']; ?><?php } ?>
        </h1>
    </div>

    <div class="stats<?php if($user['cover_art'] == 'cover_art.jpeg') { ?> no-cover<?php } ?>">
        <?php if($user['ban_list'] == 0) { ?>
            <?php if($data['post_num_user'] != 0) { ?>
                <label class="required"><?= lang('Posts-m'); ?>:</label>
                <span class="right">
                    <a title="<?= lang('Posts-m'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/posts">
                        <?= $data['post_num_user']; ?>
                    </a>
                </span>
                <br>
            <?php } ?>
            <?php if($data['answer_num_user'] != 0) { ?>
                <label class="required"><?= lang('Answers'); ?>:</label>
                <span class="right">
                    <a title="<?= lang('Answers'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/answers">
                        <?= $data['answer_num_user']; ?>
                    </a>
                </span>
                <br>
            <?php } ?>
            <?php if($data['comment_num_user'] != 0) { ?>
                <label class="required"><?= lang('Comments'); ?>:</label>
                <span class="right">
                    <a title="<?= lang('Comments'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/comments">
                        <?= $data['comment_num_user']; ?>
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
        <?php } else { ?>
        ...
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
            <?= $data['trust_level']['trust_name']; ?> <sup class="date">TL<?= $user['trust_level']; ?></sup>
        </div>

        <h2><?= lang('Contacts'); ?></h2>
        
        <?php if($user['website']) { ?>    
            <div class="boxline">
                <label for="name"><?= lang('URL'); ?>:</label>
                <a href="<?= $user['website']; ?>" rel="noopener nofollow ugc">
                    <span class="indent"><?= $user['website']; ?></span>
                </a>
            </div>
        <?php } ?>
        <?php if($user['location']) { ?> 
            <div class="boxline">
                <label for="name"><?= lang('City'); ?>:</label>
                <span class="indent"><?= $user['location']; ?></span>
            </div>
        <?php } else { ?>
            <div class="boxline">
                <label for="name"><?= lang('City'); ?>:</label>
                <span class="indent">...</span>
            </div>
        <?php } ?>
        <?php if($user['public_email']) { ?> 
            <div class="boxline">
                <label for="name"><?= lang('E-mail'); ?>:</label>
                <a href="mailto:<?= $user['public_email']; ?>" rel="noopener nofollow ugc">
                    <span class="indent"><?= $user['public_email']; ?></span>
                </a>
            </div>
        <?php } ?>
        <?php if($user['skype']) { ?>
            <div class="boxline">
                <label for="name"><?= lang('Skype'); ?>:</label>
                <a class="indent" href="skype:<?= $user['skype']; ?>" rel="noopener nofollow ugc">
                    <span class="indent"><?= $user['skype']; ?></span>
                </a>
            </div>
        <?php } ?>
        <?php if($user['twitter']) { ?>
            <div class="boxline">
                <label for="name"><?= lang('Twitter'); ?>:</label>
                <a href="https://twitter.com/<?= $user['twitter']; ?>" rel="noopener nofollow ugc">
                    <span class="indent"><?= $user['twitter']; ?></span>
                </a>
            </div>
        <?php } ?>
        <?php if($user['telegram']) { ?>
            <div class="boxline">
                <label for="name"><?= lang('Telegram'); ?>:</label>
                <a href="tg://resolve?domain=<?= $user['telegram']; ?>" rel="noopener nofollow ugc">
                    <span class="indent"><?= $user['telegram']; ?></span>
                </a>
            </div>
        <?php } ?>
        <?php if($user['vk']) { ?>
            <div class="boxline">
                <label for="name"><?= lang('VK'); ?>:</label>
                <a href="https://vk.com/<?= $user['vk']; ?>" rel="noopener nofollow ugc">
                    <span class="indent"><?= $user['vk']; ?></span>
                </a>
            </div> 
        <?php } ?>    

        <?php if($user['my_post'] != 0) { ?>
            <br>
            <h3><?= lang('Selected Post'); ?></h3>

            <div class="post-telo">
                <div class="post-body">
                    <a href="/post/<?= $onepost['post_id']; ?>/<?= $onepost['post_slug']; ?>">
                        <h2><?= $onepost['post_title']; ?></h2>
                    </a>

                    <div class="lowercase"> 
                        <img class="ava" alt="<?= $user['login']; ?>" src="<?= user_avatar_url($user['avatar'], 'small'); ?>">
                        <a class="date" href="/u/<?= $user['login']; ?>"><?= $user['login']; ?></a> 
                        
                        <span class="indent"> &#183; </span> 
                        <span class="date"><?= $onepost['post_date'] ?></span>
                        
                        <span class="indent"> &#183; </span> 
                        <a class="date"  href="/s/<?= $onepost['space_slug']; ?>" title="<?= $onepost['space_name']; ?>">
                            <?= $onepost['space_name']; ?>
                        </a> 
                        
                        <?php if($onepost['post_answers_num'] !=0) { ?> 
                            <a class="date right" href="/post/<?= $onepost['post_id']; ?>/<?= $onepost['post_slug']; ?>">
                                <i class="icon bubbles"></i> <?= $onepost['post_answers_num']; ?>  
                            </a>
                        <?php } ?>
                    </div>
                </div>                        
            </div>
        <?php } ?>
    </div>    
    </div>
</div>
</main>
    <aside>
        <div class="profile-box-telo white-box">
            <div class="inner-padding">
                <h3 class="badge"><?= lang('Badges'); ?></h3>
                <div class="profile-badge">
                    <?php if($user['id'] < 50) { ?>
                        <i title="<?= lang('Joined in the early days'); ?>" class="icon badge"></i>
                    <?php } ?>
                    <?php foreach ($data['badges'] as $badge) { ?>
                        <?= $badge['badge_icon']; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if($uid['trust_level'] > 4) { ?>
            <div class="profile-box-telo white-box">
                <div class="inner-padding">
                    <h3 class="badge"><?= lang('Admin'); ?></h3>
                    <div class="menu-info">
                        <a href="/admin/user/<?= $user['id']; ?>/edit">
                            <i class="icon settings red"></i>
                            <?= lang('Edit'); ?>
                        </a>
                        <a href="/admin/badge/user/add/<?= $user['id']; ?>">
                            <i class="icon badge"></i>
                            <?= lang('Reward the user'); ?>
                        </a>
                        <hr>
                        id<?= $user['id']; ?> | <?= $user['email']; ?>
                    </div>
                </div>
            </div>            
        <?php } ?>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>