<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">  
        <div class="messages">
            <h1><?= $data['title'] ?></h1>
            <?php if (!empty($data['messages'])) { ?>
  
                <?php foreach ($data['messages'] as  $msg) { ?>
                    <div class="msg-telo<?php if (!$msg['unread'] > 0) { ?> active<?php } ?>">
                        <?php if($msg['sender_uid'] == $uid['id']) {  ?>
                             Я  |  <?= $msg['update_time']; ?> <br>
                        <?php } else { ?>
                             От 
                             <a href="/u/<?= $msg['msg_user']['login']; ?>">
                                <?= $msg['msg_user']['login']; ?> 
                             </a>
                                |  <?= $msg['update_time']; ?> <br>
                        <?php } ?>
    
                        <a href="/messages/read/<?= $msg['id']; ?>">
                            <?php if ($msg['unread']) { ?>
                                 Есть <?= $msg['unread']; ?> сообщений
                            <?php } else { ?>
                                 Посмотреть писем <?= $msg['count']; ?>
                            <?php } ?>
                        </a>
                        
                   </div>
                <?php } ?>
           
            <?php } else { ?>
            
                У вас нет диалогов
             
            <?php } ?>
        </div>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>