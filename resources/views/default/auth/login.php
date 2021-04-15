<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="left-ots">
        <h1 class="head"><?= $data['h1']; ?></h1>
        <div class="box wide">
            <form class="" action="/login" method="post">
                <?php csrf_field(); ?>
                <div class="boxline">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="">
                </div>
                <div class="boxline">
                    <label for="password"><?= lang('Password'); ?></label>
                    <input type="password" name="password" id="password" value="">
                </div>
                <div class="boxline">
                    <input type="checkbox" id="rememberme" name="rememberme" value="1">
                    <label class="form-check-label" for="rememberme"><?= lang('Remember me'); ?></label>
                </div>
                <div class="row">
                    <div class="boxline">
                        <button type="submit" class="button-primary"><?= lang('Sign in'); ?></button>
                        <small>
                            <?php if($GLOBALS['conf']['invite'] != 1) { ?>
                                <span class="left-ots"><a href="/register"><?= lang('Sign up'); ?></a></span>
                            <?php } ?>
                            <span class="left-ots"><a href="/recover"><?= lang('forgot-password'); ?>?</a></span>
                        </small>
                    </div>
                </div>
            </form>

            <?php if($GLOBALS['conf']['invite'] == 1) { ?>
                <?= lang('no-invate-txt'); ?> 
            <?php } ?>
        </div>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>
