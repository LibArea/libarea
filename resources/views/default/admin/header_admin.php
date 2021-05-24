<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
     
        <?php getRequestHead()->output(); ?>
 
        <?php if (isset($post['post_is_delete'])) { ?>  
            <meta name="robots" content="noindex" />
        <?php } ?>

        <link rel="icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/favicon.png">

        <link rel="stylesheet" href="/assets/css/style.css">
        <link rel="stylesheet" href="/assets/css/admin.css">
        
        <?php if($uid['id']) { ?>
            <script src="/assets/js/admin.js"></script>
        <?php } ?> 
    </head>
<body class="bd">
<header>  
<div class="wrap">

    <div class="menu-left"> 
      
                <?php if($uid['uri'] == '/') { ?>
                   <a title="<?= lang('Home'); ?>" class="logo" href="/">LoriUP</a>
                <?php } else { ?>
                    <a title="<?= lang('Home'); ?>" class="logo" href="/"><i class="icon home"></i> 
                        <span class="slash no-mob">
                            <span class="sl">\</span> <?= lang('LoriUP'); ?>
                        </span>
                    </a>
                <?php } ?>
          

            <div class="nav<?php if( $uid['uri'] == '/admin') { ?> active<?php } ?>">
                <a class="home" title="<?= lang('Admin'); ?>" href="/admin">
                    <?= lang('Admin'); ?>
                </a>
            </div>
            <div class="nav<?php if( $uid['uri'] == '/admin/spaces') { ?> active<?php } ?>">
                <a class="home" title="<?= lang('Space'); ?>" href="/admin/spaces">
                    <?= lang('Space'); ?>
                </a>
            </div>
            <div class="nav<?php if( $uid['uri'] == '/admin/invitations') { ?> active<?php } ?>"> 
                <a class="home" title="<?= lang('Invites'); ?>" href="/admin/invitations">
                    <?= lang('Invites'); ?>
                </a>
            </div>
            <div class="nav<?php if( $uid['uri'] == '/admin/comments') { ?> active<?php } ?>">
                <a class="home" title="<?= lang('Comments'); ?>" href="/admin/comments">
                    <?= lang('Comments'); ?>
                </a>
            </div>
            <div class="nav<?php if( $uid['uri'] == '/admin/badges') { ?> active<?php } ?>">
                <a class="home" title="<?= lang('Badges'); ?>" href="/admin/badges">
                    <?= lang('Badges'); ?>
                </a>
            </div>
    </div>
</div>    
</header>
<?php if(!empty($post['post_title'])) { ?>
    <div id="stHeader">
        <div class="wrap">
            <a href="/"><i class="icon home"></i></a> <span class="slash">\</span> <?= $post['post_title']; ?>
        </div>
    </div>
<?php } ?>

<div class="wrap">    
  