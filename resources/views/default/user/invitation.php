<?php include TEMPLATE_DIR . '/header.php'; ?>
<main  class="w-100 max-width">
    <div class="box-users">
        <h1><?= $data['h1']; ?></h1>
         
         <?php if($uid['trust_level'] > 1) { ?>
         
            <form method="post" action="/invitation/create">
            <?php csrf_field(); ?>

                <div class="boxline">
                    <input id="link" class="add-email" type="email" name="email">
                    <input id="graburl" type="submit" name="submit" value="Создать">
                    <br>
                </div>
                 Осталось приглашений <?= 5 - $user['invitation_available']; ?> 

            </form>
             
            <h3>Приглашенные</h3>
             
            <?php if (!empty($result)) { ?> 
 
                <?php foreach ($result as $inv) { ?>
                    <?php if($inv['active_status'] == 1) { ?>
                        <div class="comm-header">
                            <img class="ava" src="/uploads/avatar/small/<?= $inv['avatar']; ?>">
                            <a href="<?= $inv['login']; ?>"><?= $inv['login']; ?></a>
                            - зарегистрировался
                        </div>
                        
                            <?php if($uid['trust_level'] == 5) { ?>
                                Была использована ссылка для: <?= $inv['invitation_email']; ?> <br>
                                <code> 
                                    <?=  $GLOBALS['conf']['url'] . '/register/invite/' . $inv['invitation_code']; ?> 
                                </code>
                            <?php } ?>
                            
                        <small>Ссылка была использована</small>
                    <?php } else { ?>
                    
                        Для   (<?= $inv['invitation_email']; ?>) можно отправить эту ссылку: <br>
                    
                        <code> 
                            <?=  $GLOBALS['conf']['url'] . '/register/invite/' . $inv['invitation_code']; ?> 
                        </code> 
                    
                    <?php } ?>

                    <br><br>
                <?php } ?> 
                
            <?php } else { ?>
             
                Пока нет приглашений
             
            <?php } ?>
        
        <?php } else { ?>
         
            Ваш уровень доверия пока не позволяет использовать инвайты.
         
        <?php } ?>
        
    </div>
</main>
<?php include TEMPLATE_DIR . '/_block/user-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?>