<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/admin', lang('Admin'), '/admin/words', lang('Stop words'), $data['meta_title']); ?>

                <div class="box badges">
                    <form action="/admin/word/add" method="post">
                        <?= csrf_field() ?>
                        <div class="boxline max-width">
                            <label class="form-label" or="post_title"><?= lang('Stop word'); ?></label>
                            <input type="text" class="form-input" name="word">
                        </div>
                        <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>