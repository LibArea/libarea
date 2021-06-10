<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<main class="admin">
    <h1 class="top">
        <a href="/admin"><?= lang('Admin'); ?></a> / <a href="/admin/badges"><?= lang('Badges'); ?></a> /
        <span class="red"><?= $data['h1']; ?></span>
    </h1>

    <div class="box badges">
        <form action="/admin/badge/edit/<?= $badge['badge_id']; ?>" method="post">
            <?= csrf_field() ?>
            <div class="boxline max-width">
                <label for="post_title">Id</label>
                <?= $badge['badge_id']; ?>
            </div>
            <div class="boxline max-width">
                <label for="post_title">Title</label>
                <input type="text" name="badge_title" value="<?= $badge['badge_title']; ?>" required>
            </div>
            <div class="boxline max-width">
                <label for="post_title">Icon</label>            
                <textarea class="add" name="badge_icon" required><?= $badge['badge_icon']; ?></textarea>
                <div class="box_h"><?= lang('For example'); ?>: &lt;i title="<?= lang('Title'); ?>" class="icon badge"&gt;&lt;/i&gt;</div>
            </div>
            <div class="boxline">
                <label for="post_title">Tl</label>
                <input type="text" name="badge_tl" value="<?= $badge['badge_tl']; ?>" required>
                <div class="box_h"><?= lang('For'); ?> TL (0 <?= lang('by default'); ?>)</div>
            </div>
            <div class="boxline">
                <label for="post_title">Score</label>
                <input type="text" name="badge_score" value="<?= $badge['badge_score']; ?>" required>
                <div class="box_h"><?= lang('Reward Weight'); ?></div>
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('Description'); ?></label>
                <textarea class="add" name="badge_description" required><?= $badge['badge_description']; ?></textarea>
            </div>
            <input type="submit" name="submit" value="<?= lang('Edit'); ?>" />
        </form>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?> 