<?php include TEMPLATE_DIR . '/header.php'; ?>
    <section>
    <div class="wrap">
        <div class="gravatar">
            <img src="/images/user/<?php echo $data['avatar']; ?>">
        </div>

        <h1><?php echo $data['login']; ?> / <?php echo $data['name']; ?></h1>

        <div class="box wide">
            <label class="required">Профиль:</label>
            <span class="d">id:<?php echo $data['id']; ?></span>

            <br>

            <label class="required">Присоединился::</label>
            <span class="d"><?php echo $data['created_at']; ?></span>


            <br>

            <?php if($data['post_num_user'] != 0) { ?>
                <label class="required">Постов:</label>
                <span class="d">
                    <a title="Всего постов <?php echo $data['login']; ?>" href="/newest/<?php echo $data['login']; ?>">
                        <?php echo $data['post_num_user']; ?>
                    </a>
                </span>
            <?php } ?>

            <br>

            <?php if($data['comm_num_user'] != 0) { ?>
                <label class="required">Комментариев:</label>
                <span class="d">
                    <a title="Все комментарии <?php $data['login']; ?>" href="/threads/<?php echo $data['login']; ?>">
                        <?php echo $data['comm_num_user']; ?>
                    </a>
                </span>
            <?php } ?>

                <br>

            <label class="required">О себе:</label>
            <span class="na">
                <?php if($data['about']) { ?>
                    <?php echo $data['about']; ?>
                <?php } else { ?>
                    Загадка...
                <?php } ?>
            </span>

        </div>
    </div>
    </section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
