<?php include TEMPLATE_DIR . '/header.php'; ?>
    <section>
        <div class="wrap">
            <h1>Участники</h1>
            <div class="telo">
                <?php foreach($data['users'] as $ind => $user) {  ?>
                   id:<?php echo $user['id']; ?>
                    <a href="/u/<?php echo $user['login']; ?>"><?php echo $user['login']; ?></a>
                    (<?php echo $user['name']; ?>) <br>
                <?php } ?>
            </div>
        </div>
    </section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
