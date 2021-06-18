<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding">
                <div class="max-width">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
                        </li>
                    </ul>
                    <h1><?= lang('Access restricted'); ?></h1>

                    <p><i><?= lang('The profile is being checked'); ?>...</p>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 