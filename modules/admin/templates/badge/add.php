<?php include TEMPLATE_ADMIN_DIR . '/_block/header-admin.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="pt5 pr15 pb5 pl15">
                <?= breadcrumb('/admin', lang('Admin'), '/admin/badges', lang('Badges'), $data['meta_title']); ?>

                <div class="box badges">
                    <form action="/admin/badge/add" method="post">
                        <?= csrf_field() ?>
                        <div class="boxline max-width">
                            <label class="form-label" or="post_title">Title</label>
                            <input type="text" minlength="4" class="form-input" name="badge_title" value="" required>
                            <div class="box_h">4 - 25 <?= lang('characters'); ?></div>
                        </div>
                        <div class="boxline max-width">
                            <label class="form-label" for="post_title">Icon</label>
                            <textarea class="add" name="badge_icon" required></textarea>
                            <div class="box_h"><?= lang('For example'); ?>: &lt;i title="<?= lang('Title'); ?>" class="light-icon-brush"&gt;&lt;/i&gt;</div>
                        </div>
                        <div class="boxline">
                            <label class="form-label" for="post_title">Tl</label>
                            <input type="text" class="form-input" name="badge_tl" value="0" required>
                            <div class="box_h"><?= lang('For'); ?> TL (0 <?= lang('by default'); ?>)</div>
                        </div>
                        <div class="boxline">
                            <label class="form-label" for="post_title">Score</label>
                            <input class="form-input" type="text" name="badge_score" value="10" required>
                            <div class="box_h"><?= lang('Reward Weight'); ?></div>
                        </div>
                        <div class="boxline max-width">
                            <label class="form-label" for="post_title"><?= lang('Description'); ?></label>
                            <textarea class="add" minlength="12" name="badge_description" required></textarea>
                            <div class="box_h">12 - 250 <?= lang('characters'); ?></div>
                        </div>
                        <input type="submit" class="button" name="submit" value="<?= lang('Add'); ?>" />
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include TEMPLATE_ADMIN_DIR . '/_block/footer-admin.php'; ?>