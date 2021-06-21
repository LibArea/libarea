<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <h1><a href="/u/<?= $uid['login']; ?>/messages"><?= lang('All messages'); ?></a> / <?= $data['h1']; ?> </h1>
                    
                <form action="/messages/send" method="post">
                <?= csrf_field() ?>
                    <input type="hidden" name="recipient" value="<?= $data['recipient_user']['id']; ?>" />
                    <textarea rows="3" id="message" class="mess" placeholder="<?= lang('Write'); ?>..." type="text" name="message" /></textarea>
                    <p>
                    <input type="submit" name="submit" value="<?= lang('Reply'); ?>" class="submit">    
                    </p>
                </form>

                <?php if ($data['list']) { ?>
                    <?php foreach ($data['list'] AS $key => $val) { ?>
                  
                        <div class="msg-telo">

                            <?php if ($val['uid'] == $uid['id']) { ?>
                            
                                <img class="avatar left" src="<?= user_avatar_url($uid['avatar'], 'max'); ?>"> 
                               
                            <div class="message left">
                                
                            <?php } else { ?>

                                <a class="right" href="/u/<?= $val['login']; ?>">
                                    <img class="avatar left" src="<?= user_avatar_url($val['avatar'], 'max'); ?>">
                                </a> 

                            <div class="message right">

                                <a class="left" href="/u/<?= $val['login']; ?>"> 
                                    <?= $val['login']; ?>: &nbsp;
                                </a> 
                            
                            <?php } ?>  
         
                                <?= $val['message']; ?>

                                <div class="date">
                                    <?= $val['add_time']; ?> 
                                    
                                    <?php if ($val['receipt'] AND $val['uid'] == $uid['id']) { ?> 
                                        <?= lang('It was read'); ?> (<?= $val['receipt']; ?>)
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding big">
                <?= lang('Under development'); ?>...
            </div>
        </div>   
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>