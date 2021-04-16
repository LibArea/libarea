<?php include TEMPLATE_DIR . '/header.php'; ?>
<main>
    <h1 class="head"><?= $data['title']; ?></h1>
    <div class="box wide">
        <form class="" action="/recover/send/pass" method="post">
            <?php csrf_field(); ?>
            <div class="boxline">
                <label for="password"><?= lang('New password'); ?></label>
                <input type="text" name="password" id="password"> 
            </div>
            <div class="row">
                <div class="boxline">
                    <input type="hidden" name="code" id="code" value="<?= $data['code']; ?>">
                    <input type="hidden" name="user_id" id="user_id" value="<?= $data['user_id']; ?>">
                    <button type="submit" class="button-primary"><?= lang('Reset'); ?></button>
                </div>
            </div>
        </form>
            <div class="boxline">
                <a href="/register"><?= lang('Sign up'); ?></a> &emsp;
                <a href="/login"><?= lang('Sign in'); ?></a>
            </div>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
