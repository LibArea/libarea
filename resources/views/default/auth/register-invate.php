<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <h1 class="head"><?= $data['h1']; ?></h1>
    <div class="box wide">
        <form class="" action="/register/add" method="post">
            <?php csrf_field(); ?>
            <div class="boxline">
                <label for="login"><?= lang('Nickname'); ?></label>
                <input type="text" name="login" id="login">
            </div>
            <div class="boxline">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?= $invate['invitation_email']; ?>">
            </div>
            <div class="boxline">
                <label for="password"><?= lang('Password'); ?></label>
                <input type="password" name="password" id="password">
            </div>
             <div class="boxline">
                <label for="password_confirm"><?= lang('repeat-password'); ?></label>
                <input type="password" name="password_confirm" id="password_confirm">
            </div> 
            <div class="boxline">
                <div class="boxline">
                    <input type="hidden" name="invitation_code" id="invitation_code" value="<?= $invate['invitation_code']; ?>">
                    <input type="hidden" name="invitation_id" id="invitation_id" value="<?= $invate['uid']; ?>">
                    <button type="submit" class="button-primary"><?= lang('Sign up'); ?></button>
                </div>
            </div>
        </form>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>