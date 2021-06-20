<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <h1><?= $data['h1']; ?></h1>
                <br>
                <i>В стадии разработки...</i>
                <br><br>
            </div>
        </div>
    </main>
    <aside>
        <div class="white-box">
            <div class="inner-padding space-tags">
                <?= lang('domains-desc'); ?>.
            </div>                        
        </div>
    </aside>
</div>    
<?php include TEMPLATE_DIR . '/footer.php'; ?> 