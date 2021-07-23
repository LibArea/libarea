<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <a class="right small button vertical-ind" href="/notifications/delete"><?= lang('I read'); ?></a>
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['login'], lang('Profile'), $data['h1']); ?>
                 
                <?php if (!empty($list)) { ?>

                    <?php foreach ($list as  $notif) { ?>
                    
                        <div class="fl<?php if($notif['read_flag'] == 0) { ?> active<?php } ?>">
                        
                        <?php if($notif['action_type'] == 1) { ?>
                            <?php if($notif['read_flag'] == 0) { ?>
                                <i class="light-icon-mail middle red"></i>
                            <?php } else { ?>
                                <i class="light-icon-mail middle"></i>
                            <?php } ?>
                           
                            <a href="/u/<?= $notif['login']; ?>"><?= $notif['login']; ?></a> 
                            <?= lang('Wrote to you'); ?> 
                            <a href="/notifications/read/<?= $notif['notification_id']; ?>"><?= lang('Message'); ?></a>
                        <?php } ?>
                        
                        <?php if($notif['action_type'] == 2) { ?>
                            <?= lang('Wrote a post'); ?> 
                        <?php } ?> 
                         
                        <?php if($notif['action_type'] == 3) { ?>
                            <?= lang('Replied to post'); ?>
                            <i class="light-icon-book middle"></i>
                        <?php } ?>  
                         
                        <?php if($notif['action_type'] == 10 || $notif['action_type'] == 11 || $notif['action_type'] == 12) { ?>
                            <i class="light-icon-user middle"></i>
                            <a href="/u/<?= $notif['login']; ?>">@<?= $notif['login']; ?></a> 
                            <?= lang('appealed to you'); ?>
                            <a class="ntf2 lowercase" href="/notifications/read/<?= $notif['notification_id']; ?>">
                                <?php if($notif['action_type'] == 10) { ?>
                                    <?= lang('in post'); ?>
                                <?php } elseif ($notif['action_type'] == 11) { ?>
                                    <?= lang('in answer'); ?>
                                <?php } else { ?>
                                    <?= lang('in the comment'); ?>
                                <?php } ?>
                            </a> 
                        <?php } ?> 
                        <?php if($notif['action_type'] == 15) { ?>
                            <a class="ntf2 lowercase" href="/notifications/read/<?= $notif['notification_id']; ?>">
                                <i class="light-icon-activity middle red"></i>
                                <?= lang('Audit'); ?>
                            </a>
                            |
                            <a class="ntf2 lowercase" href="/admin/user/<?= $notif['sender_uid']; ?>/edit">
                                <?= $notif['login']; ?>
                            </a>  
                            |  
                            <a class="ntf2 lowercase" href="/admin/audit">
                                <?= lang('Admin'); ?>
                            </a>
                        <?php } ?> 
                        <span class="lowercase">
                            <?php if($notif['action_type'] == 4) { ?>
                                <?php if($notif['read_flag'] == 0) { ?>
                                    <i class="light-icon-messages middle red"></i>
                                <?php } else { ?>
                                    <i class="light-icon-messages middle"></i>
                                <?php } ?>
                                <a href="/u/<?= $notif['login']; ?>"><?= $notif['login']; ?></a> 
                                    <?= lang('Wrote'); ?>  
                                <a class="ntf2" href="/notifications/read/<?= $notif['notification_id']; ?>">
                                    <?= lang('Comment'); ?>
                                </a>
                                <?= lang('to your answer'); ?> 
                            <?php } ?>
                        </span>
                            <span class="small gray"> —  <?= $notif['add_time']; ?></span>
                            <?php if($notif['read_flag'] == 0) { ?>&nbsp;<sup class="red">✔</sup><?php } ?>
                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <?= lang('No notifications yet'); ?>...
                <?php } ?>
            </div>
        </div>              
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('info_notifications'); ?>
            </div>
        </div>   
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>