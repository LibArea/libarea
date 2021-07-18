<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/admin', lang('Admin'), '/admin/badges', lang('Badges'), $data['meta_title'] .' '. $user['login']); ?>

                <div class="box badges">
                    <form action="/admin/badge/user/add" method="post">
                        <?= csrf_field() ?>

                        <div class="boxline">  
                            <label class="form-label" for="post_content"><?= lang('Badge'); ?></label>
                            <select class="form-input" name="badge_id">
                                <?php foreach ($badges as $badge) { ?>
                                    <option value="<?= $badge['badge_id']; ?>"> <?= $badge['badge_title']; ?></option>
                                <?php } ?>
                            </select>
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