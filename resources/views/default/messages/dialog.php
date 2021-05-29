<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">

    <h1><a href="/u/<?= $uid['login']; ?>/messages"><?= lang('All messages'); ?></a> / <?= $data['h1']; ?> </h1>
        
    <form action="/messages/send" method="post">
    <?= csrf_field() ?>
        <input type="hidden" name="recipient" value="<?= $data['recipient_user']['id']; ?>" />
        
        Для <img src="/uploads/users/avatars/small/<?= $data['recipient_user']['avatar']; ?>" class="msg-ava">
            <a href="/u/<?= $data['recipient_user']['login']; ?>" class="msg-user">
            <?= $data['recipient_user']['login']; ?>
        </a><br>
                                                                                                                                                           
        <textarea rows="3" id="message" class="mess" placeholder="<?= lang('Write'); ?>..." type="text" name="message" /></textarea>
        <p>
        <input type="submit" name="submit" value="<?= lang('Reply to'); ?>" class="submit">    
        </p>
    </form>

      
    <?php if ($data['list']) { ?>
        <?php foreach($data['list'] AS $key => $val) { ?>
      
            <div class="msg-telo<?php if ($val['uid'] == $uid['id']) { ?> class="active"<?php } ?>">

                <?php if ($val['uid'] == $uid['id']) { ?>
                   Я | <?= $val['add_time']; ?>
                <?php } else { ?>
                    <a href="/u/<?= $val['login']; ?>">
                        <?= $val['login']; ?> 
                    </a> | <?= $val['add_time']; ?>
                <?php } ?></a>  
                <br>
                
                <?= $val['message']; ?>
                
                <div class="footer">
                    <?php if ($val['receipt'] AND $val['uid'] == $uid['id']) { ?> 
                
                      Было прочитанно - (<?= $val['receipt']; ?>)
        
                    <?php } else { ?> 
                        <!-- Отправлено на e-mail, возможно --> 
                    <?php } ?>
                </div>

            </div>
            
        <?php } ?>
    <?php } ?>
               
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>