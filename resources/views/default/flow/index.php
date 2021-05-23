<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <div class="flow">
        <h1><?= $data['h1']; ?></h1>

        <div id="content"></div>

        <?php include TEMPLATE_DIR . '/flow/flow-form.php'; ?>
    </div>
</main>
<aside>
    <?= lang('info_flow'); ?>
</aside>
<?php include TEMPLATE_DIR . '/footer.php'; ?>