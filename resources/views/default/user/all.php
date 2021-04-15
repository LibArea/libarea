<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/_block/left-menu.php'; ?>
<main class="w-75">
    <div class="left-ots">
        <div class="box-users">
            <h1><?= $data['h1']; ?></h1>
            <div class="all-users">
            <?php foreach($users as $ind => $user) { ?>
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
                        <?php if($user['name']) { ?>
                           <small> <?= $user['name']; ?> </small>
                        <?php } else { ?>
                            
                        <?php } ?>
                        </div>
                    </div>    
                </div>        
            <?php } ?>
            </div> 
        </div>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>