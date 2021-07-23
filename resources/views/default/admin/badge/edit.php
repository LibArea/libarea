<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <?= breadcrumb('/admin', lang('Admin'), '/admin/badges', lang('Badges'), $data['meta_title']); ?>

                <div class="box badges">
                    <form action="/admin/badge/edit/<?= $badge['badge_id']; ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="boxline max-width">
                            <label for="post_title">Id</label>
                            <?= $badge['badge_id']; ?>
                        </div>
                        <div class="boxline max-width">
                            <label class="form-label" for="post_title">Title</label>
                            <input class="form-input" type="text" name="badge_title" value="<?= $badge['badge_title']; ?>" required>
                        </div>
                        <div class="boxline max-width">
                            <label class="form-label" for="post_title">Icon</label>            
                            <textarea class="add" name="badge_icon" required><?= $badge['badge_icon']; ?></textarea>
                            <div class="box_h"><?= lang('For example'); ?>: &lt;i title="<?= lang('Title'); ?>" class="light-icon-brush"&gt;&lt;/i&gt; https://icons.lightvue.org/icons</div>
                        </div>
                        <div class="boxline">
                            <label class="form-label" for="post_title">Tl</label>
                            <input class="form-input" type="text" name="badge_tl" value="<?= $badge['badge_tl']; ?>" required>
                            <div class="box_h"><?= lang('For'); ?> TL (0 <?= lang('by default'); ?>)</div>
                        </div>
                        <div class="boxline">
                            <label class="form-label" for="post_title">Score</label>
                            <input class="form-input" type="text" name="badge_score" value="<?= $badge['badge_score']; ?>" required>
                            <div class="box_h"><?= lang('Reward Weight'); ?></div>
                        </div>
                        <div class="boxline max-width">
                            <label class="form-label" for="post_title"><?= lang('Description'); ?></label>
                            <textarea class="add" name="badge_description" required><?= $badge['badge_description']; ?></textarea>
                        </div>
                        <input type="submit" class="button" name="submit" value="<?= lang('Edit'); ?>" />
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>