<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="flow">
        <h1><?= $data['h1']; ?></h1>

        <div id="content"></div>

        <?php include TEMPLATE_DIR . '/flow/flow-form.php'; ?>
    </div>
</main>
<?php include TEMPLATE_DIR . '/footer.php'; ?>