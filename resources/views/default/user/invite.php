<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <h1><?= $data['h1']; ?></h1>
                <div class="box wide">
                    <form class="" action="/invite" method="post">
                        <?php csrf_field(); ?>
                        <div class="boxline">
                            <label for="email"><?= lang('Code'); ?></label>
                            <input type="text" name="invite" id="invite">
                        </div>
                        <div class="row">
                            <div class="boxline">
                                <button type="submit" class="button-primary"><?= lang('Sign in'); ?></button>
                                <span class="menu-lf size-13"><a href="/recover"><?= lang('Forgot your password'); ?>?</a></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('Under development'); ?>...
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>