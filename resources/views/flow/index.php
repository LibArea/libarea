<?php include TEMPLATE_DIR . '/header.php'; ?>
<main class="w-100">
    <div class="left-ots flow">
        <h1><?= $data['h1']; ?></h1>

        <div id="content"></div>

        <?php include TEMPLATE_DIR . '/flow/flow-form.php'; ?>
    </div>
</main>
<aside id="sidebar"> 
    <div class="menu-info">
       <small>Чат и лента активности находится в стадии разработки.</small>
    </div>
</aside> 
<?php include TEMPLATE_DIR . '/footer.php'; ?>