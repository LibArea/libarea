<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">  
        <div class="messages dialog">
            <a class="right" href="/messages">Все сообщения</a>
            
            <form action="/messages/send" method="post">
            <?= csrf_field() ?>
				<input type="hidden" name="recipient" value="<?= $data['recipient_user']['id']; ?>" />
                
				Для <a href="/users/<?= $data['recipient_user']['login']; ?>" class="msg-user">
                    <?= $data['recipient_user']['login']; ?>
                </a><br>
 																																								   
				<textarea rows="3" id="message" class="mess" placeholder="Напишите..." type="text" name="message" /></textarea>
				<p>
                <input type="submit" name="submit" value="Ответить" class="submit">    
				</p>
			</form>
       
              
            <ul>
            <?php if ($data['list']) { ?>
                <?php foreach($data['list'] AS $key => $val) { ?>
              
                    <div <?php if ($val['uid'] == $usr['id']) { ?> class="active"<?php } ?>>
                        <div class="msg-telo">
                            <?php if ($val['uid'] == $usr['id']) { ?>
                                Я | <?php echo $val['add_time']; ?>
                            <?php } else { ?>
                                <a href="/u/<?= $val['login']; ?>">
                                    <?= $val['login']; ?> 
                                </a> | <?php echo $val['add_time']; ?>
                            <?php } ?></a>  
                            <br>
                            
                            <?= $val['message']; ?>
                            
                            <div class="footer">
                                <?php if ($val['receipt'] AND $val['uid'] == $usr['id']) { ?> 
                            
                                  Было прочитанно - (<?= $val['receipt']; ?>)
                    
                                <?php } else { ?> 
                                    <!-- Отправлено на e-mail, возможно --> 
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                <?php } ?>
            <?php } ?>
            </ul>
     
                           
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>