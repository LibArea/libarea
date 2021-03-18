<?php include TEMPLATE_DIR . '/header.php'; ?>
    <section>
        <div class="wrap">
            <h1><?= $data['title']; ?></h1>
            <div class="telo">
                <?php foreach($data['users'] as $ind => $user) {  ?>
                    id:<?php echo $user['id']; ?>
                    <a href="/u/<?= $user['login']; ?>"><?= $user['login']; ?></a>
                    <?php if($user['name']) { ?>
                        (<?= $user['name']; ?>) 
                    <?php } ?>
                    <br>
                <?php } ?>
            </div>
        </div>
    </section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
