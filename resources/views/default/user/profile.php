<?php include TEMPLATE_DIR . '/header.php'; ?>

<?php if($user['cover_art'] != 'cover_art.jpeg') { ?>
    <div class="profile-box-cover" style="background-image: url(<?= user_cover_url($user['cover_art']); ?>); background-position: 50% 50%;min-height: 310px;">
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
                            <i class="light-icon-mail"></i>
                        </a>
                    <?php } ?>    
                <?php } else { ?>
                    <a class="right pm"  href="/u/<?= $uid['login']; ?>/setting">
                        <i class="light-icon-edit"></i>
                    </a> 
                <?php } ?>
            </div>
        <?php } ?>    
        <div class="profile-ava">
            <?= user_avatar_img($user['avatar'], 'max', $user['login'], 'ava'); ?>
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
            <?php if($data['posts_count'] > 0) { ?>
                <label class="required"><?= lang('Posts-m'); ?>:</label>
                <span class="right">
                    <a title="<?= lang('Posts-m'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/posts">
                        <?= $data['posts_count']; ?>
                    </a>
                </span>
                <br>
            <?php } ?>
            <?php if($data['answers_count'] > 0) { ?>
                <label class="required"><?= lang('Answers'); ?>:</label>
                <span class="right">
                    <a title="<?= lang('Answers'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/answers">
                        <?= $data['answers_count']; ?>
                    </a>
                </span>
                <br>
            <?php } ?>
            <?php if($data['comments_count'] > 0) { ?>
                <label class="required"><?= lang('Comments'); ?>:</label>
                <span class="right">
                    <a title="<?= lang('Comments'); ?> <?= $user['login']; ?>" href="/u/<?= $user['login']; ?>/comments">
                        <?= $data['comments_count']; ?>
                    </a>
                </span>
                <br>
            <?php } ?>
            
            <?php if($data['spaces_user']) { ?>
                <br>
                <div class="bar-title small"><?= lang('Created by'); ?></div>
                <span class="d">
                    <?php foreach ($data['spaces_user'] as  $space) { ?>
                        <div class="profile-space">
                            <a class="bar-space-telo" href="/s/<?= $space['space_slug'];?>">
                                <?= spase_logo_img($space['space_img'], 'small', $space['space_name'], 'space-logo'); ?>
                                <span class="bar-name small"><?= $space['space_name'];?></span>
                            </a> 
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
            <i class="light-icon-calendar middle"></i>
            <span class="middle">
                <span class="ts"><?= $user['created_at']; ?></span>  —  
                <?= $data['trust_level']['trust_name']; ?> <sup class="date">TL<?= $user['trust_level']; ?></sup>
            </span>
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

                    <div class="small lowercase"> 
                        <a class="gray" href="/u/<?= $user['login']; ?>">
                            <?= user_avatar_img($user['avatar'], 'small', $user['login'], 'ava'); ?>
                            <span class="indent"></span> 
                            <?= $user['login']; ?>
                        </a> 
                        
                        <span class="indent"> &#183; </span> 
                        <span class="gray"><?= $onepost['post_date'] ?></span>
                        
                        <span class="indent"> &#183; </span> 
                        <a class="gray"  href="/s/<?= $onepost['space_slug']; ?>" title="<?= $onepost['space_name']; ?>">
                            <?= $onepost['space_name']; ?>
                        </a> 
                        
                        <?php if($onepost['post_answers_count'] !=0) { ?> 
                            <a class="gray right" href="/post/<?= $onepost['post_id']; ?>/<?= $onepost['post_slug']; ?>">
                                <span class="indent"></span> 
                                <i class="light-icon-messages middle"></i> 
                                <?= $onepost['post_answers_count']; ?>  
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
                        <i title="<?= lang('Joined in the early days'); ?>" class="light-icon-award middle green"></i> 
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
                        <a class="gray" href="/admin/user/<?= $user['id']; ?>/edit">
                            <i class="light-icon-settings middle"></i>
                            <span class="middle"><?= lang('Edit'); ?></span>
                        </a>
                        <a class="gray" href="/admin/badge/user/add/<?= $user['id']; ?>">
                            <i class="light-icon-award middle"></i>
                            <span class="middle"><?= lang('Reward the user'); ?></span>
                        </a>
                        <hr>
                        <span class="gray">id<?= $user['id']; ?> | <?= $user['email']; ?></span>
                    </div>
                </div>
            </div>            
        <?php } ?>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>