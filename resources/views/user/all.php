<?php include TEMPLATE_DIR . '/header.php'; ?>
    <section>
        <div class="wrap">
        <div class="box-users">
             
                <h1><?= $data['title']; ?></h1>
                <div class="all-users">
                <?php foreach($data['users'] as $ind => $user) {  ?>
                    <div class="column">
                        <div class="user_card">
                            <div>
                                <a href="/u/<?= $user['login']; ?>">
                                    <img class="gravatar" alt="<?= $user['login']; ?>" src="/uploads/avatar/<?php echo $user['avatar']; ?>">
                                </a>
                            </div>
                            <div class="box-footer">
                            <a href="/u/<?= $user['login']; ?>"><?= $user['login']; ?></a>
                            <br>
                        
                            id:<?php echo $user['id']; ?>
                   
                            <?php if($user['name']) { ?>
                                (<?= $user['name']; ?>) 
                            <?php } ?>
                            </div>
                        </div>    
                    </div>        
                <?php } ?>
                </div> 
        </div>
        </div>
    </section>
    <br>
<?php include TEMPLATE_DIR . '/footer.php'; ?>