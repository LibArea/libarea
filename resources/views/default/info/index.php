<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/', lang('Home'), '/info', lang('Info'), $data['h1']); ?>

                <?= $data['content']; ?>
            </div>
        </div>
    </main>
    <aside>
        <?php include TEMPLATE_DIR . '/_block/info-page-menu.php'; ?>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>