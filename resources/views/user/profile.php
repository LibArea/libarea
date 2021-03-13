<?php include TEMPLATE_DIR . '/header.php'; ?>
    <section>
    <div class="wrap">
        <div class="gravatar">
            <img src="/images/user/<?= $data['avatar']; ?>">
        </div>

        <h1><?= $data['login']; ?> / <?= $data['name']; ?></h1>

        <div class="box wide">
            <label class="required">Профиль:</label>
            <span class="d">id:<?= $data['id']; ?></span>

            <br>

            <label class="required">Присоединился::</label>
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
            <span class="na">
                <?php if($data['about']) { ?>
                    <?= $data['about']; ?>
                <?php } else { ?>
                    Загадка...
                <?php } ?>
            </span>

        </div>
    </div>
    </section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
