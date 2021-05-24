<?php include TEMPLATE_DIR . '/admin/header_admin.php'; ?>
<main class="admin">
    <h1 class="top">
        <a href="/admin"><?= lang('Admin'); ?></a> / <a href="/admin/badges"><?= lang('Badges'); ?></a> /
        <span class="red"><?= $data['h1']; ?> </span>
    </h1>

    <div class="box badges">
        <form action="/admin/badge/add" method="post">
            <?= csrf_field() ?>
            <div class="boxline max-width">
                <label for="post_title">Title</label>
                <input type="text" name="badge_title" value="">
            </div>
            <div class="boxline max-width">
                <label for="post_title">Icon</label>            
                <textarea class="add" name="badge_icon"></textarea>
            </div>
            <div class="boxline max-width">
                <label for="post_title"><?= lang('Description'); ?></label>
                <textarea class="add" name="badge_description"></textarea>
            </div>
            <input type="submit" name="submit" value="<?= lang('Add'); ?>" />
        </form>
    </div> 
</main>
<?php include TEMPLATE_DIR . '/admin/footer_admin.php'; ?> 