<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb0 pl15">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), $data['h1']); ?>
                <?php include TEMPLATE_DIR . '/_block/setting-nav.php'; ?>
             </div> 
        </div>             
        <div class="white-box">
            <div class="pt15 pr15 pb5 pl15">
                <form action="/users/setting/security/edit" method="post" enctype="multipart/form-data">
                    <?php csrf_field(); ?>
                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('Old'); ?></label>
                        <input type="text" class="form-input" name="password" id="password" value="<?= $data['password']; ?>">
                    </div>
                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('New'); ?></label>
                        <input type="text" minlength="8" class="form-input" name="password2" id="password2" value="<?= $data['password2']; ?>">
                    </div>
                    <div class="boxline">
                        <label class="form-label" for="name"><?= lang('Repeat'); ?></label>
                        <input type="text" minlength="8" class="form-input" name="password3" id="password3" value="<?= $data['password3']; ?>">
                    </div>
                    <div class="boxline">
                        <input type="hidden" name="nickname" id="nickname" value="">
                        <button type="submit" class="button"><?= lang('Edit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('info_security'); ?>
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>