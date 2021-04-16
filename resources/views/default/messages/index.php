<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="w-100">
    <div class="messages">
        <h1><?= $data['title'] ?></h1>
        <?php if (!empty($data['messages'])) { ?>

            <?php foreach ($data['messages'] as  $msg) { ?>

                <div class="msg-telo<?php if (!$msg['unread'] > 0) { ?> active<?php } ?>">
                    <?php if($msg['sender_uid'] == $uid['id']) {  ?>
                         Я  |  <?= $msg['update_time']; ?> <br>
                    <?php } else { ?>
                         От 
                         
                        <img src="/uploads/avatar/small/<?= $msg['msg_user']['avatar']; ?>" class="msg-ava">
                         <a href="/u/<?= $msg['msg_user']['login']; ?>">
                            <?= $msg['msg_user']['login']; ?> 
                         </a>
                            |  <?= $msg['update_time']; ?> <br>
                    <?php } ?>

                    <a href="/messages/read/<?= $msg['id']; ?>">
                        <?php if ($msg['unread']) { ?>
                             Есть <?= $msg['count']; ?> <?= $msg['unread_num']; ?>
                        <?php } else { ?>
                             Посмотреть  
                            <?php if($msg['count'] != 0) { ?> 
                                <?= $msg['count']; ?>  <?= $msg['count_num']; ?>
                            <?php } ?>    
                        <?php } ?>
                    </a>
                    
               </div>
            <?php } ?>
       
        <?php } else { ?>
            <div class="no-content">У вас нет диалогов</div>
        <?php } ?>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>