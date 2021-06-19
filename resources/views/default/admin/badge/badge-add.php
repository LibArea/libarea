<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / 
                    <a href="/admin/badges"><?= lang('Badges'); ?></a> /
                    <span class="red"><?= $data['h1']; ?> </span>
                </h1>

                <div class="box badges">
                    <form action="/admin/badge/add" method="post">
                        <?= csrf_field() ?>
                        <div class="boxline max-width">
                            <label for="post_title">Title</label>
                            <input type="text" name="badge_title" value="" required>
                        </div>
                        <div class="boxline max-width">
                            <label for="post_title">Icon</label>            
                            <textarea class="add" name="badge_icon" required></textarea>
                            <div class="box_h"><?= lang('For example'); ?>: &lt;i title="<?= lang('Title'); ?>" class="icon badge"&gt;&lt;/i&gt;</div>
                        </div>
                        <div class="boxline">
                            <label for="post_title">Tl</label>
                            <input type="text" name="badge_tl" value="0" required>
                            <div class="box_h"><?= lang('For'); ?> TL (0 <?= lang('by default'); ?>)</div>
                        </div>
                        <div class="boxline">
                            <label for="post_title">Score</label>
                            <input type="text" name="badge_score" value="10" required>
                            <div class="box_h"><?= lang('Reward Weight'); ?></div>
                        </div>            
                        <div class="boxline max-width">
                            <label for="post_title"><?= lang('Description'); ?></label>
                            <textarea class="add" name="badge_description" required></textarea>
                        </div>
                        <input type="submit" name="submit" value="<?= lang('Add'); ?>" />
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>