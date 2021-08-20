<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb0 pl15">
                <?= breadcrumb('/', lang('Home'), '/u/' . $uid['user_login'], lang('Profile'), $data['h1']); ?>
                <?php include TEMPLATE_DIR . '/_block/favorite-nav.php'; ?>
            </div>
        </div>

        <?php include TEMPLATE_DIR . '/_block/post.php'; ?>

    </main>
    <aside>
        <div class="white-box">
            <div class="p15">
                <?= lang('info_preferences'); ?>...
            </div>
        </div>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>