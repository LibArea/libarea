<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-75">
    <div class="flow">
        <div id="content"></div>
        <?php include TEMPLATE_DIR . '/flow/flow-form.php'; ?>
    </div>
</main>
<aside>
    <h1><?= $data['h1']; ?></h1>
    <?= lang('info_flow'); ?>
</aside>
<script async src="/assets/js/common.js"></script>
<?php print getRequestResources()->getBottomStyles(); ?>
<?php print getRequestResources()->getBottomScripts(); ?> 
</body>
</html> 