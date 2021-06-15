<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
         
        <h1>
            <?= $data['h1'] ?>
            <a class="right small button" href="/notifications/delete"><?= lang('I read'); ?></a>
        </h1>
         
        <?php if (!empty($list)) { ?>

            <?php foreach ($list as  $notif) { ?>
            
                <div class="fl<?php if($notif['read_flag'] == 0) { ?> active<?php } ?>">
                
                <?php if($notif['action_type'] == 1) { ?>
                    <?php if($notif['read_flag'] == 0) { ?>
                        <span class="red"><i class="icon envelope"></i></span>
                    <?php } else { ?>
                        <i class="icon envelope"></i>
                    <?php } ?>
                   
                    <a href="/u/<?= $notif['login'] ?>"><?= $notif['login'] ?></a> 
                    <?= lang('Wrote to you'); ?> 
                    <a href="/messages/read/<?= $notif['notification_id'] ?>"><?= lang('Message'); ?></a>
                <?php } ?>
                
                <?php if($notif['action_type'] == 2) { ?>
                    <?= lang('Wrote a post'); ?> 
                <?php } ?> 
                 
                <?php if($notif['action_type'] == 3) { ?>
                    <?= lang('Replied to post'); ?>
                    <i class="icon action-undo"></i>
                <?php } ?>  
                 
                <?php if($notif['action_type'] == 10 || $notif['action_type'] == 11 || $notif['action_type'] == 12) { ?>
                    <i class="icon user"></i> 
                    <a href="/u/<?= $notif['login'] ?>">@<?= $notif['login'] ?></a> 
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
                <span class="lowercase">
                    <?php if($notif['action_type'] == 4) { ?>
                        <?php if($notif['read_flag'] == 0) { ?>
                            <i class="icon bubbles red"></i>
                        <?php } else { ?>
                            <i class="icon bubbles"></i>
                        <?php } ?>
                        <a href="/u/<?= $notif['login'] ?>"><?= $notif['login'] ?></a> 
                            <?= lang('Wrote'); ?>  
                        <a class="ntf2" href="/notifications/read/<?= $notif['notification_id']; ?>">
                            <?= lang('Comment'); ?>
                        </a>
                        <?= lang('to your answer'); ?> 
                    <?php } ?>
                </span>
                    <span class="date"> —  <?= $notif['add_time']; ?></span>
                    <?php if($notif['read_flag'] == 0) { ?>&nbsp;<sup class="red">✔</sup><?php } ?>
                </div>
            <?php } ?>

        <?php } else { ?>
            <?= lang('No notifications yet'); ?>...
        <?php } ?>
    </main>
    <aside>
        <?= lang('info_notifications'); ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>