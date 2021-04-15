<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="left-ots">
        <h1><?= $data['title'] ?></h1>
        <?php if (!empty($list)) { ?>

            <?php foreach ($list as  $notif) { ?>
                <div class="fl<?php if($notif['read_flag'] == 0) { ?> active<?php } ?>">
                <?php if($notif['action_type'] == 1) { ?>
                    <svg class="md-icon moon">
                        <use xlink:href="/assets/svg/icons.svg#mail"></use>
                    </svg>
                   
                    <a href="/u/<?= $notif['login'] ?>"><?= $notif['login'] ?></a> 
                    написал вам 
                    <a href="/messages/read/<?= $notif['connection_type'] ?>">сообщение</a>
                 
                    <span class="date"> — <?= $notif['add_time']; ?></span>
                
                    <?php if($notif['read_flag'] == 0) { ?><sup class="red">✔</sup><?php } ?>
                
                <?php } ?>
                
                 <?php if($notif['action_type'] == 2) { ?>
                    <svg class="md-icon moon">
                        <use xlink:href="/assets/svg/icons.svg#message"></use>
                    </svg>
                   
                    <a href="/u/<?= $notif['login'] ?>"><?= $notif['login'] ?></a> 
                    ответил вам в  
                    <a class="ntf2" href="/notifications/read/<?= $notif['connection_type']; ?>"">комментарии</a>
                
                    <span class="date"> —  <?= $notif['add_time']; ?></span>
                
                    <?php if($notif['read_flag'] == 0) { ?><sup class="red">✔</sup><?php } ?>
                
                <?php } ?>

                </div>
            <?php } ?>

        <?php } else { ?>
             Уведомлений пока нет...
        <?php } ?>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>