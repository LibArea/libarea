<?php include TEMPLATE_DIR . '/header.php'; ?>
<section>
    <div class="wrap">
        <div class="left-ots">
            <h1 class="head"><?= $data['title']; ?></h1>
            <div class="box wide">
                <form class="" action="/recover/send/pass" method="post">
                    <?php csrf_field(); ?>
                    <div class="boxline">
                        <label for="password">Новый пароль</label>
                        <input type="text" name="password" id="password">
                    </div>
                    <div class="row">
                        <div class="boxline">
                            <input type="hidden" name="code" id="code" value="<?= $data['code']; ?>">
                            <input type="hidden" name="user_id" id="user_id" value="<?= $data['user_id']; ?>">
                            <button type="submit" class="button-primary">Сбросить</button>
                        </div>
                    </div>
                </form>
                    <div class="boxline">
                        <a href="/register">Регистрация</a> &emsp;
                        <a href="/login">Войти</a>
                    </div>
            </div>
        </div>
    </div>
</section>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
