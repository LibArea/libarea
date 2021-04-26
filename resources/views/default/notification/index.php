<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100 max-width notifications">
    <h1><?= $data['title'] ?></h1>
    
    <?php if (!empty($list)) { ?>

        <?php foreach ($list as  $notif) { ?>
            <div class="fl<?php if($notif['read_flag'] == 0) { ?> active<?php } ?>">
            <?php if($notif['action_type'] == 1) { ?>
            
                <?php if($notif['read_flag'] == 0) { ?>
                    <span class="red"><icon name="action-undo"></icon></span>
                <?php } else { ?>
                    <icon name="action-undo"></icon>
                <?php } ?>
               
                <a href="/u/<?= $notif['login'] ?>"><?= $notif['login'] ?></a> 
                написал вам 
                <a href="/messages/read/<?= $notif['connection_type'] ?>">сообщение</a>
             
                <span class="date"> — <?= $notif['add_time']; ?></span>
            
                <?php if($notif['read_flag'] == 0) { ?><sup class="red">✔</sup><?php } ?>
            
            <?php } ?>
            
             <?php if($notif['action_type'] == 2) { ?>
                
                
                <?php if($notif['read_flag'] == 0) { ?>
                    <span class="red"><icon name="envelope"></icon></span>
                <?php } else { ?>
                    <icon name="envelope"></icon>
                <?php } ?>
                
                <a href="/u/<?= $notif['login'] ?>"><?= $notif['login'] ?></a> 
                
                    ответил вам в  
                
                <a class="ntf2" href="/notifications/read/<?= $notif['connection_type']; ?>">
                    комментарии
                </a>
            
                <span class="date"> —  <?= $notif['add_time']; ?></span>
            
                <?php if($notif['read_flag'] == 0) { ?><sup class="red">✔</sup><?php } ?>
            
            <?php } ?>

            </div>
        <?php } ?>

    <?php } else { ?>
         Уведомлений пока нет...
    <?php } ?>
</main>
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>