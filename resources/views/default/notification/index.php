<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75">
        <a class="right edit-space" href="/notifications/delete">Я прочитал</a>
        <h1><?= $data['h1'] ?></h1>
         
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
                    написал вам 
                    <a href="/messages/read/<?= $notif['connection_type'] ?>">сообщение</a>
                 
                    <span class="date"> — <?= $notif['add_time']; ?></span>
                
                    <?php if($notif['read_flag'] == 0) { ?><sup class="red">✔</sup><?php } ?>
                
                <?php } ?>
                
                <?php if($notif['action_type'] == 2) { ?>
                    Написал пост
                <?php } ?> 
                 
                <?php if($notif['action_type'] == 3) { ?>
                    Ответил на пост
                    <i class="icon action-undo"></i>
                <?php } ?>  
                 
                 <?php if($notif['action_type'] == 4) { ?>
                    
                    <?php if($notif['read_flag'] == 0) { ?>
                        <i class="icon bubbles red"></i>
                    <?php } else { ?>
                        <i class="icon bubbles"></i>
                    <?php } ?>
                    
                    <a href="/u/<?= $notif['login'] ?>"><?= $notif['login'] ?></a> 
                    
                        написал  
                    
                    <a class="ntf2" href="/notifications/read/<?= $notif['connection_type']; ?>">
                        комментарий
                    </a>
                
                       на ваш ответ
                    <span class="date"> —  <?= $notif['add_time']; ?></span>
                
                    <?php if($notif['read_flag'] == 0) { ?><sup class="red">✔</sup><?php } ?>
                
                <?php } ?>

                </div>
            <?php } ?>

        <?php } else { ?>
             Уведомлений пока нет...
        <?php } ?>
    </main>
    <aside>
        <?= lang('info_notifications'); ?>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>