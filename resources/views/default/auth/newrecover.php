<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <div class="white-box">
                    <div class="inner-padding">
                        <h1><?= $data['h1']; ?></h1>
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
                                        <small>
                                            <?php if(!Lori\Config::get(Lori\Config::PARAM_INVITE)) { ?>
                                                <span class="indent"><a href="/register"><?= lang('Sign up'); ?></a></span>
                                            <?php } ?>
                                            <span class="indent"><a href="/login"><?= lang('Sign in'); ?></a></span>
                                        </small>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?>
