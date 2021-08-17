<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1><?= $data['h1']; ?></h1>
                <div class="box wide">
                    <form class="" action="/recover/send/pass" method="post">
                        <?php csrf_field(); ?>
                        <div class="boxline">
                            <label class="form-label" for="password">
                                <?= lang('New password'); ?>
                            </label>
                            <input class="form-input" type="text" name="password" id="password">
                        </div>
                        <div class="row">
                            <div class="boxline">
                                <input type="hidden" name="code" id="code" value="<?= $data['code']; ?>">
                                <input type="hidden" name="user_id" id="user_id" value="<?= $data['user_id']; ?>">
                                <button type="submit" class="button"><?= lang('Reset'); ?></button>
                                <?php if (!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
                                    <span class="mr5 ml5 size-13"><a href="/register"><?= lang('Sign up'); ?></a></span>
                                <?php } ?>
                                <span class="mr5 ml5 size-13"><a href="/login"><?= lang('Sign in'); ?></a></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>