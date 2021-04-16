<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="left-ots max-width">
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a title="<?= lang('Home'); ?>" href="/"><?= lang('Home'); ?></a>
            </li>
            <li class="breadcrumb-item">
                <a title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
            </li>
        </ul>
        <h1><?= $data['h1']; ?></h1>

        <p>Пожалуйста, относитесь к этому сайту с таким же уважением, как относитесь к собственному дому. </p>
        <p>
            Нет нарушений законодательства РФ. <br>
            Нет нападкам.<br>
            Нет спаму.
        </p>
        <p><i>В стадии разработки...</i></p>
    </div>
</main>
<?php include TEMPLATE_DIR . '/_block/info-menu.php'; ?>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 