<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
                    </li>
                </ul>
                
                <h1><?= $data['h1']; ?></h1>
                
                <?= $data['content']; ?>
            </div>
        </div>
    </main>
    <aside>
        <?php include TEMPLATE_DIR . '/_block/info-page-menu.php'; ?>
    </aside>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 