<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="w-75 info">
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
      
    </main>
    <?php include TEMPLATE_DIR . '/_block/info-page-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?> 