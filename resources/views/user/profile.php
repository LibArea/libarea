<?php include TEMPLATE_DIR . '/header.php'; ?>
    <section>
    <div class="wrap">
        <div class="profile">
            <div class="gravatar">
                <img src="/images/user/<?= $data['avatar']; ?>">
            </div>

            <h1>
                <?= $data['login']; ?> 
            
                <?php if($data['name']) { ?> / <?= $data['name']; ?><?php } ?>
            
                <?php if($data['uid']['id'] > 0) { ?>   
                        <?php if($data['uid']['login'] != $data['login']) { ?>
                            <small> - <a href="/u/<?= $data['login']; ?>/messages">Отправить сообщение</a></small>
                        <?php } ?>
                <?php } ?>
            </h1>

            <div class="box wide">
                <label class="required">Профиль:</label>
                <span class="d">id:<?= $data['id']; ?></span>

                <br>

                <label class="required">Присоединился:</label>
                <span class="d"><?= $data['created_at']; ?></span>

                <br>

                <?php if($data['post_num_user'] != 0) { ?>
                    <label class="required">Постов:</label>
                    <span class="d">
                        <a title="Всего постов <?= $data['login']; ?>" href="/newest/<?= $data['login']; ?>">
                            <?= $data['post_num_user']; ?>
                        </a>
                    </span> <br>
                <?php } ?>

                <?php if($data['comm_num_user'] != 0) { ?>
                    <label class="required">Комментариев:</label>
                    <span class="d">
                        <a title="Все комментарии <?= $data['login']; ?>" href="/threads/<?= $data['login']; ?>">
                            <?= $data['comm_num_user']; ?>
                        </a>
                    </span>  <br>
                <?php } ?>

                <label class="required">О себе:</label>
                <span class="na about">
                    <?php if($data['about']) { ?>
                        <?= $data['about']; ?>
                    <?php } else { ?>
                        Загадка...
                    <?php } ?>
                </span>

            </div>
        </div>    
    </div>
    </section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
